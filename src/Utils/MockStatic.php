<?php

namespace Instante\Tests\Utils;

use Mockery\MockInterface;

class MockStatic
{
    /**
     * Utility class when you need to alias a static class that also have static properties.
     * Example usage: you need to test that foo() calls A::hello():
     * <code>
     * function foo() {
     *   A::$message = 'helloworld';
     *   A::hello();
     * }
     * </code>
     *
     * would be pain with raw Mockery, as the following will not work because of accessing
     * undeclared static property:
     * <code>
     * $m = Mockery::mock('alias:A');
     * $m->shouldReceive('hello')->once();
     * foo();
     * </code>
     *
     * This method provides you with static class combining mock abilities with required
     * static property definitions, so this will work as needed:
     * <code>
     * $m = MockStatic::mock('A', ['message']);
     * $m->shouldReceive('hello')->once();
     * foo();
     * </code>
     *
     * @param string $type mocked static class name
     * @param array $staticProperties list of static properties the class should have
     * @return MockInterface expectation handler
     */
    public static function mock($type, $staticProperties = [])
    {
        $mock = mock('stdClass');
        $mockClassName = get_class($mock);
        $nsAndClass = explode('\\', strrev($type), 2);
        $mockedClassName = strrev($nsAndClass[0]);

        $code = '';
        if (count($nsAndClass) > 1) {
            $ns = strrev($nsAndClass[1]);
            $code .= "namespace $ns;\n";
        }

        $code .= "class $mockedClassName extends \\$mockClassName {\n";
        foreach ($staticProperties as $property) {
            $code .= "  public static \$$property;\n";
        }
        $code .= "}\n";

        eval($code);

        return $mock;
    }
}
