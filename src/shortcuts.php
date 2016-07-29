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
