<?php

namespace RickSelby\Tests\Server;

use App\Services\ScriptService;
use App\Services\ServerService;
use Illuminate\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
use RickSelby\Tests\BaseSetup;

abstract class ServerSetup extends BaseSetup
{
    /** @var ServerService */
    protected $server;

    /** @var \PHPUnit_Framework_MockObject_MockObject|ScriptService */
    protected $scriptService;

    public function setUp()
    {
        parent::setUp();
        putenv('AC_SERVER_LOG_PATH='.$this->vfsRoot->url());
        $this->scriptService = $this->createMock(ScriptService::class);
        $this->server = new ServerService(
            $this->createMock(LoggerInterface::class),
            $this->app->make(Filesystem::class),
            $this->scriptService
        );
    }
}
