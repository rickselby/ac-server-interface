<?php

namespace RickSelby\Tests\Server;

use org\bovigo\vfs\vfsStream;

class LogTest extends ServerSetup
{
    public function testGetServerLogRecent()
    {
        $content = 'wibble';
        $this->vfsRoot->addChild(vfsStream::newFile('acServer.log')->setContent($content));
        $this->assertEquals($content, $this->server->getLogFile());
    }

    public function testGetServerLogLast()
    {
        $content = 'wubble';
        $this->vfsRoot->addChild(vfsStream::newFile('acServer.log.last')->setContent($content));
        $this->assertEquals($content, $this->server->getLogFile());
    }

    public function testGetServerLogNone()
    {
        $this->assertEquals('', $this->server->getLogFile());
    }
}
