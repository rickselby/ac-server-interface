<?php

namespace RickSelby\Tests\Config;

use Psr\Log\LoggerInterface;
use RickSelby\Tests\TestCase;
use App\Services\ConfigService;
use Illuminate\Filesystem\Filesystem;

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
        $this->checkConfigFile(ConfigService::ENTRY_LIST, $content);
    }

    public function testSetServerConfig()
    {
        $content = 'bar';
        $returned = $this->config->updateServerConfig($content);
        $this->assertTrue($returned);
        $this->checkConfigFile(ConfigService::SERVER_CONFIG, $content);
    }

    private function checkConfigFile($file, $content)
    {
        \Storage::disk('ac_server')->assertExists($file);
        $this->assertEquals($content, \Storage::disk('ac_server')->get($file));
    }
}
