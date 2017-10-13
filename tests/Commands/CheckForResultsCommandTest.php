<?php

namespace RickSelby\Tests\Config;

use App\Services\ResultsService;
use RickSelby\Tests\TestCase;

class CheckForResultsCommandTest extends TestCase
{
    /** @var ResultsService */
    protected $resultsService;

    public function setUp()
    {
        parent::setUp();
        $this->resultsService = $this->createMock(ResultsService::class);
        $this->app->bind(ResultsService::class, function () {
            return $this->resultsService;
        });
    }

    public function testCommandChecksForResults()
    {
        $this->resultsService->expects($this->once())->method('checkForResults');
        $this->artisan('server:results-check');
    }
}
