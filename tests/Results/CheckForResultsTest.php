<?php

namespace RickSelby\Tests\Results;

class CheckForResultsTest extends ResultsSetup
{
    public function testWhenNoResults()
    {
        $this->withMasterUrl();
        $this->guzzleClient->expects($this->never())->method('request');
        $this->results->checkForResults();
    }

    public function testWhenResultsRead()
    {
        $this->withMasterUrl();
        $this->addResultFileAsRead('sth');
        $this->guzzleClient->expects($this->never())->method('request');
        $this->results->checkForResults();
    }

    public function testWhenResultsUnread()
    {
        $this->withMasterUrl();
        $this->addResultFile('sth');
        $this->guzzleClient->expects($this->once())->method('request')->with('POST', env('MASTER_SERVER_URL'));
        $this->results->checkForResults();
    }

    public function testWhenMultipleResultsUnread()
    {
        $this->withMasterUrl();
        $this->addResultFile('foo');
        $this->addResultFile('bar');
        $this->addResultFile('baz');
        $this->guzzleClient->expects($this->exactly(3))->method('request')->with('POST', env('MASTER_SERVER_URL'));
        $this->results->checkForResults();
    }

    public function testWhenNoMasterUrl()
    {
        $this->withoutMasterUrl();
        $this->addResultFile('foo');
        $this->addResultFile('bar');
        $this->addResultFile('baz');
        $this->guzzleClient->expects($this->never())->method('request');
        $this->results->checkForResults();
    }

    protected function withMasterUrl()
    {
        putenv('MASTER_SERVER_URL=sth');
    }

    protected function withoutMasterUrl()
    {
        putenv('MASTER_SERVER_URL');
    }
}
