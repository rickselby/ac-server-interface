<?php

namespace RickSelby\Tests\Config;

use App\Services\ConfigService;
use Illuminate\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
use RickSelby\Tests\TestCase;

class ConfigTest extends TestCase
{
    /** @var ConfigService */
    protected $config;

    public function setUp()
    {
        parent::setUp();
        $this->config = new ConfigService(
            $this->createMock(LoggerInterface::class),
            $this->app->make(Filesystem::class)
        );

        \Storage::fake('ac_server');
        \Storage::fake('local');
    }

    public function testSetEntryList()
    {
        $content = 'foo';
        $returned = $this->config->updateEntryList($content);
        $this->assertTrue($returned);
        $this->checkConfigFile(ConfigService::entryList, $content);
    }

    public function testSetServerConfig()
    {
        $content = 'bar';
        $returned = $this->config->updateServerConfig($content);
        $this->assertTrue($returned);
        $this->checkConfigFile(ConfigService::serverConfig, $content);
    }

    private function checkConfigFile($file, $content)
    {
        \Storage::disk('ac_server')->assertExists($file);
        $this->assertEquals($content, \Storage::disk('ac_server')->get($file));
    }
}
