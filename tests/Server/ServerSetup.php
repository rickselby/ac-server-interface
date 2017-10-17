<?php

namespace RickSelby\Tests\Server;

use Psr\Log\LoggerInterface;
use RickSelby\Tests\TestCase;
use App\Services\ScriptService;
use App\Services\ServerService;

abstract class ServerSetup extends TestCase
{
    /** @var ServerService */
    protected $server;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ScriptService */
    protected $scriptService;

    public function setUp()
    {
        parent::setUp();
        $this->scriptService = $this->createMock(ScriptService::class);
        $this->server = new ServerService(
            $this->createMock(LoggerInterface::class),
            $this->scriptService
        );
    }
}
