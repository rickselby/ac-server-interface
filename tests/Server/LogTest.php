<?php

namespace RickSelby\Tests\Server;

use App\Services\ServerService;

class LogTest extends ServerSetup
{
    public function setUp()
    {
        parent::setUp();
        \Storage::fake('ac_server');
    }

    /**
     * @dataProvider logFileProvider
     */
    public function testGetServerLogs($logFile)
    {
        $content = 'wibble';
        \Storage::disk('ac_server')->put($logFile, $content);
        $this->assertEquals($content, $this->server->getLogFile());
    }

    public function testGetServerLogNone()
    {
        $this->assertEquals('', $this->server->getLogFile());
    }

    /*************************************************************************/

    public function logFileProvider()
    {
        return array_map(function($element) {
            return [$element];
        }, ServerService::logFiles);
    }

}
