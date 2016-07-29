<?php
namespace Instante\Tests\Meta\Presenters\Fakes;

use Instante\Tests\Presenters\Fakes\FakeSessionSection;
use Instante\Tests\Meta\SandboxTestBootstrap;
use Tester\Assert;

require __DIR__ . '/../bs-presenters.php';

SandboxTestBootstrap::prepareUnitTest();

//only getSection and hasSection are implemented

$s = new FakeSessionSection;
$s->{'a'} = 'b';
$s['B'] = 'A';
Assert::same('b', $s['a']);
Assert::same('A', $s->{'B'});

Assert::true(isset($s['a'], $s->{'a'}));

$x = [];
foreach ($s as $key => $val) {
    $x[$key] = $val;
}
Assert::equal(['a' => 'b', 'B' => 'A'], $x);

unset($s->{'a'});
unset($s['B']);
Assert::false(isset($s['a']));
Assert::false(isset($s->{'B'}));
