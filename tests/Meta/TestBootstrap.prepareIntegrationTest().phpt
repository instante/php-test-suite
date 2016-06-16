<?php
namespace Instante\Tests\Meta;

use Instante\Tests\TestBootstrap;
use KdybyModule\CliPresenter;
use Nette\Configurator;
use Nette\DI\Container;
use Tester\Assert;

require __DIR__ . '/../../src/test-bootstrap.php';

class CliPresenterHackTestBootstrap extends TestBootstrap
{
    protected static function configureConfigurator(Configurator $configurator, $configDir)
    {
        parent::configureConfigurator($configurator, $configDir); // TODO: Change the autogenerated stub
        if (class_exists(CliPresenter::class)) {
            // avoid loading CliPresenter service without required Kdyby\Console\Application
            $configurator->addConfig(['application' => ['scanComposer' => FALSE]]);
        }
    }
}

CliPresenterHackTestBootstrap::$tempDir = __DIR__ . '/../temp';
$container = CliPresenterHackTestBootstrap::prepareIntegrationTest(realpath(__DIR__ . '/../sandbox/tests'));

Assert::type(Container::class, $container, 'container created');

Assert::same('bar', $container->getParameters()['database']['dbname'], 'dbname_test copied to dbname');
Assert::same(TestBootstrap::$tempDir, $container->getParameters()['tempDir'], 'tempDir passed to container');
Assert::true($container->getParameters()['localNeon'], 'local.neon loaded');
