<?php
namespace Instante\Tests\Meta\Presenters;

use Instante\Tests\Presenters\PresenterTester;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Latte\Engine;
use Latte\Loaders\StringLoader;
use Nette;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Application\UI\Presenter;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

class PresenterTesterTest extends TestCase
{
    public function testConstructor()
    {
        new PresenterTester('Instante\Tests\Presenters\TestPresenter', SandboxTestBootstrap::$tempDir);
        new PresenterTester(new TestPresenter, SandboxTestBootstrap::$tempDir);
        new PresenterTester(function () { return new TestPresenter; }, SandboxTestBootstrap::$tempDir);
        Assert::true(TRUE, 'Passing this test indicates that PresenterTester constructor accepts all desired argument types.');
    }

    public function testBasicTest()
    {
        $tester = new PresenterTester(new TestPresenter, SandboxTestBootstrap::$tempDir);
        $result = $tester->runPresenter();
        Assert::same('Hello world', $result->getResponseBody(), 'Basic presenter execution');
    }

    public function testInjectDependencies()
    {
        $tester = new PresenterTester(new TestInjectPresenter, SandboxTestBootstrap::$tempDir);
        $dc = $tester->getDependencyContainer();
        $dc->addDependencies([
            'foo' => new Foo(1),
            'baz' => ($baz = new Foo(4)),
        ]);
        $dc->addDependencies([
            'foo' => new Foo(2),
            'bar' => new Foo(3),
            'foos' => [new Foo, new Fooo],
        ]);
        $tester->runPresenter();
        $presenter = $baz->presenter;
        Assert::type('Nette\Application\UI\Presenter', $presenter, 'Presenter should have been passed via $baz');
        Assert::same(2, $presenter->foo->no, 'overriden repeatedly injected property');
        Assert::same(3, $presenter->bar->no, 'injected via @inject annotation');
        Assert::same(4, $presenter->baz->no, 'injected single property not wrapped into array via inject*() method');
        Assert::type(Foo::class, $presenter->foo2, 'injected two properties via inject*() method');
        Assert::type(Fooo::class, $presenter->fooo, 'injected two properties via inject*() method');
    }

    public function testCheckInstanceUsedOnce()
    {
        $tester = new PresenterTester(new TestPresenter, SandboxTestBootstrap::$tempDir);
        $tester->runPresenter();
        Assert::exception(function () use ($tester) {
            $tester->runPresenter();
        }, 'Nette\InvalidStateException');

        //should not fail - presenter passed by class or factory method can be used multiple times
        $tester = new PresenterTester(TestPresenter::class, SandboxTestBootstrap::$tempDir);
        $tester->runPresenter();
        $tester->runPresenter();
        $tester = new PresenterTester(function () { return new TestPresenter; }, SandboxTestBootstrap::$tempDir);
        $tester->runPresenter();
        $tester->runPresenter();
    }
}

class Foo
{
    public $no;

    public $presenter;

    public function __construct($no = 0)
    {
        $this->no = $no;
    }

}

class Fooo
{
}

class TestPresenter extends Presenter
{

    /**
     * @return ITemplate
     */
    protected function createTemplate()
    {
        $engine = new Engine;
        $engine->setLoader(new StringLoader);
        return (new Template($engine))->setFile('Hello world');
    }
}

class TestInjectPresenter extends TestPresenter
{
    /** @var Foo @inject */
    public $foo;

    /** @var Foo @inject */
    public $bar;

    public $baz;

    public $fooo;

    public $foo2;

    public function injectBaz(Foo $baz)
    {
        $this->baz = $baz;
    }

    public function injectFoos(Foo $foo2, Fooo $fooo)
    {
        $this->foo2 = $foo2;
        $this->fooo = $fooo;
    }

    /**
     * @return ITemplate
     */
    protected function createTemplate()
    {
        if ($this->baz) {
            $this->baz->presenter = $this;
        }
        return parent::createTemplate();
    }
}

(new PresenterTesterTest())->run();
