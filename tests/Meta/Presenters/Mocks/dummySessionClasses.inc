<?php

namespace Instante\Tests\Meta\Presenters\Fakes;

use /** @noinspection PhpDeprecationInspection */
    Nette\Http\ISessionStorage;

/** @noinspection PhpDeprecationInspection */
class DummySessionStorage implements ISessionStorage
{

    function open($savePath, $sessionName)
    {
    }

    function close()
    {
    }

    function read($id)
    {
    }

    function write($id, $data)
    {
    }

    function remove($id)
    {
    }

    function clean($maxlifetime)
    {
    }
}

class DummySessionHandler implements \SessionHandlerInterface
{
    public function close()
    {
    }

    public function destroy($session_id)
    {
    }

    public function gc($maxlifetime)
    {
    }

    public function open($save_path, $session_id)
    {
    }

    public function read($session_id)
    {
    }

    public function write($session_id, $session_data)
    {
    }
}
