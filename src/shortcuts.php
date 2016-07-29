<?php

/**
 * shortcut to \Mockery::mock().
 *
 * @return \Mockery\MockInterface
 */
function mock()
{
    $args = func_get_args();

    return call_user_func_array([\Mockery::class, 'mock'], $args);
}

/**
 * shortcut to \Mockery::spy() - creating a spy/stub version of mock (ignores method calls with undefined expectations)
 *
 * @return \Mockery\MockInterface
 */
function spy()
{
    $args = func_get_args();

    return call_user_func_array([\Mockery::class, 'spy'], $args);
}
