<?php

namespace Instante\Tests\Doctrine;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Migration;
use Doctrine\DBAL\Migrations\MigrationException;
use Kdyby\Doctrine\EntityManager;
use Nette\DI\Container;
use Tester\Environment;

class DoctrineTester
{
    /** @var EntityManager */
    private $em;

    /** @var Configuration */
    private $doctrineMigrationConfiguration;

    /** @var string */
    private $tempDir;

    /**
     * @param EntityManager $em
     * @param Configuration $doctrineMigrationConfiguration
     * @param string $tempDir
     */
    public function __construct(EntityManager $em, Configuration $doctrineMigrationConfiguration, $tempDir)
    {

        $this->em = $em;
        $this->doctrineMigrationConfiguration = $doctrineMigrationConfiguration;
        $this->tempDir = $tempDir;
    }

    public static function createFromContainer(Container $context)
    {
        /** @var EntityManager $em */
        $em = $context->getByType(EntityManager::class);
        /** @var Configuration $conf */
        $conf = $context->getByType(Configuration::class);
        return new self(
            $em,
            $conf,
            $context->getParameters()['tempDir']
        );
    }

    public function prepareDatabaseTest()
    {
        $this->lock();
        $this->em->clear();

        /** @var Configuration $migrationConfig */
        $migration = new Migration($this->doctrineMigrationConfiguration);
        try {
            $migration->migrate();
        } catch (MigrationException $ex) {
            if ($ex->getCode() !== 4) {
                // no migrations found; this should not break tests in early stages of development,
                // the tests will fail when they require a model anyway
                throw $ex;
            }
        }
        $this->clearDatabase();
    }

    public function clearDatabase()
    {
        $connection = $this->em->getConnection();
        $tables = $connection->getSchemaManager()->listTableNames();
        $connection->prepare('SET FOREIGN_KEY_CHECKS = 0')->execute();
        foreach ($tables as $table) {
            if ($table !== 'db_version') {
                $connection->prepare('TRUNCATE TABLE `' . $table . '`')->execute();
            }
        }
        $connection->prepare('SET FOREIGN_KEY_CHECKS = 1')->execute();
        $this->em->clear();
    }

    /** @return EntityManager */
    public function getEntityManager()
    {
        return $this->em;
    }


    private function lock()
    {
        Environment::lock('db', $this->tempDir);
    }
}
