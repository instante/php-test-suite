<?php
namespace Instante\Tests\Meta\Presenters\DI;

use Instante\Tests\Presenters\DI\DependencyContainer;
use Instante\Tests\TestBootstrap;
use Nette\Application\UI\Presenter;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

class MockInjectionPresenter extends Presenter
{
    /** @inject */
    public $a;

    /** @inject */
    public $b;

    /** @inject */
    public $notSatisfied;

    public $cc;

    public $dd;

    public $ee;

    public function injectSingle($c)
    {
        $this->cc = $c;
    }

    public function injectTwo($d, $e)
    {
        $this->dd = $d;
        $this->ee = $e;
    }
}

TestBootstrap::prepareUnitTest();

$dc = new DependencyContainer;
$dc->addDependencies([
    'a' => 'a',
    'b' => 'x',
    'single' => 'c',
]);
$dc->addDependencies([
    'b' => 'b',
    'two' => ['d', 'e'],
]);

$p = new MockInjectionPresenter;
$dc->injectTo($p);
Assert::same('a', $p->a);
Assert::same('b', $p->b);
Assert::same('c', $p->cc);
Assert::same('d', $p->dd);
Assert::same('e', $p->ee);

