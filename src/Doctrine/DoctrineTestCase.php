<?php

namespace Instante\Tests\Doctrine;

use Kdyby\Doctrine\DBALException;
use Kdyby\Doctrine\EntityManager;
use Nette\DI\Container;
use Tester\Environment;
use Tester\TestCase;

abstract class DoctrineTestCase extends TestCase
{
    /** @var DoctrineTester */
    protected $databaseTester;

    /** @var EntityManager */
    protected $em;

    /** @var bool */
    private $prepared = FALSE;

    public function __construct(DoctrineTester $doctrineTester)
    {
        $this->databaseTester = $doctrineTester;
        $this->em = $doctrineTester->getEntityManager();
    }

    public static function createFromContainer(Container $context)
    {
        return new static(DoctrineTester::createFromContainer($context));
    }


    protected function setUp()
    {
        parent::setUp();
        if (!$this->prepared) {
            try {
                $this->databaseTester->prepareDatabaseTest();
            } catch (DBALException $ex) {
                if (preg_match('~unknown database|access denied|2002|no such file~i', $ex->getMessage())) {
                    Environment::skip('No test SQL database available');
                } else {
                    throw $ex;
                }
            }
            $this->prepared = TRUE;
        } else {
            $this->databaseTester->clearDatabase();
        }
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->databaseTester->clearDatabase();
    }


}
