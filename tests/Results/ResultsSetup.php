<?php

namespace RickSelby\Tests\Results;

use App\Services\ResultsService;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use RickSelby\Tests\TestCase;

abstract class ResultsSetup extends TestCase
{
    /** @var ResultsService */
    protected $results;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Client */
    protected $guzzleClient;

    public function setUp()
    {
        parent::setUp();
        $this->guzzleClient = $this->createMock(Client::class);
        $this->results = new ResultsService(
            $this->createMock(LoggerInterface::class),
            $this->guzzleClient
        );
        $this->createResultsSeenFile();
        // bump up the precision for microtime()
        ini_set('precision', 16);

        \Storage::fake('ac_server');
        \Storage::fake('local');
    }

    protected function createResultsSeenFile()
    {
        \Storage::disk('local')->put(ResultsService::RESULTS_SENT_FILE, '');
    }

    protected function addResultFile($content = '')
    {
        // Use DateTime to enable microseconds, so we get unique filenames
        $path = ResultsService::RESULTS_DIRECTORY
            .DIRECTORY_SEPARATOR
            .\DateTime::createFromFormat('U.u', microtime(true))->format('Y-m-d-H-i-s-u');

        \Storage::disk('ac_server')->put($path, $content);

        return $path;
    }
}
