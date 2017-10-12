<?php

namespace RickSelby\Tests\Results;

use App\Services\ResultsService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Filesystem\Filesystem;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;
use Psr\Log\LoggerInterface;
use RickSelby\Tests\BaseSetup;

abstract class ResultsSetup extends BaseSetup
{
    /** @var ResultsService */
    protected $results;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Client */
    protected $guzzleClient;

    /** @var vfsStreamFile */
    protected $resultsFile;

    public function setUp()
    {
        parent::setUp();
        putenv('AC_SERVER_RESULTS_PATH=' . $this->vfsRoot->url());
        $this->guzzleClient = $this->createMock(Client::class);
        $this->results = new ResultsService(
            $this->createMock(LoggerInterface::class),
            $this->app->make(Filesystem::class),
            $this->guzzleClient
        );
        $this->createResultsSeenFile();
        // bump up the precision for microtime()
        ini_set("precision", 16);
    }

    protected function createResultsSeenFile()
    {
        $appDir = vfsStream::newDirectory('app');
        $this->resultsFile = vfsStream::newFile('results.list');
        $appDir->addChild($this->resultsFile);
        $this->vfsRoot->addChild($appDir);
    }

    protected function addResultFile($content = '')
    {
        // Use DateTime to enable microseconds, so we get unique filenames
        $file = vfsStream::newFile(
            \DateTime::createFromFormat('U.u', microtime(true))->format('Y-m-d-H-i-s-u')
        )->setContent($content);
        $this->vfsRoot->addChild($file);
        return $file;
    }

    protected function addResultFileAsRead($content = '')
    {
        $file = $this->addResultFile($content);
        file_put_contents($this->resultsFile->url(), $file->url()."\n", FILE_APPEND);
        return $file;
    }
}

