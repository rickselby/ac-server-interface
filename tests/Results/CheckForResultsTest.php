<?php

namespace RickSelby\Tests\Results;

use App\Services\ResultsService;

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
        $this->addResultFileAsSent('sth');
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

    /*************************************************************************/

    protected function addResultFileAsSent($content = '')
    {
        $path = $this->addResultFile($content);
        \Storage::disk('local')->append(ResultsService::resultsSentFile, $path);
        return $path;
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
