<?php

namespace RickSelby\Tests\Server;

class ServerServiceTest extends ServerSetup
{
    public function testStartServer()
    {
        $this->scriptService->expects($this->once())->method('run')->with('start');
        $this->server->start();
    }

    public function testStopServer()
    {
        $this->scriptService->expects($this->once())->method('run')->with('stop');
        $this->server->stop();
    }

    public function testGetStatus()
    {
        $this->scriptService->expects($this->once())->method('run')->with('status')->will($this->returnValue('status'));
        $this->server->status();
    }

    public function testIsRunning()
    {
        $this->scriptService->expects($this->once())->method('run')->with('status')->will($this->returnValue('Server is Running'));
        $this->server->isRunning();
    }

    public function testIsStopped()
    {
        $this->scriptService->expects($this->once())->method('run')->with('status')->will($this->returnValue('Server is Not Running'));
        $this->server->isStopped();
    }
}
