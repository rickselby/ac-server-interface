<?php

namespace RickSelby\Tests\Config;

use App\Services\ConfigService;
use Illuminate\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
use RickSelby\Tests\BaseSetup;

class ConfigTest extends BaseSetup
{
    /** @var ConfigService */
    protected $config;

    public function setUp()
    {
        parent::setUp();
        putenv('AC_SERVER_CONFIG_PATH='.$this->vfsRoot->url());
        $this->config = new ConfigService(
            $this->createMock(LoggerInterface::class),
            $this->app->make(Filesystem::class)
        );
    }
    
    public function testSetEntryList()
    {
        $content = 'foo';
        $returned = $this->config->updateEntryList($content);
        $this->assertTrue($returned);
        $this->checkConfigFile('entry_list.ini', $content);
    }

    public function testSetServerConfig()
    {
        $content = 'bar';
        $returned = $this->config->updateServerConfig($content);
        $this->assertTrue($returned);
        $this->checkConfigFile('server_cfg.ini', $content);
    }

    private function checkConfigFile($file, $content)
    {
        $this->assertTrue($this->vfsRoot->hasChild($file));
        $this->assertEquals($content, file_get_contents($this->vfsRoot->getChild($file)->url()));
    }

}
