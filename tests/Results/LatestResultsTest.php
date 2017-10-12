<?php

namespace RickSelby\Tests\Results;

class LatestResultsTest extends ResultsSetup
{
    public function testWhenNoResults()
    {
        $this->assertFalse($this->results->getLatestResults());
    }

    public function testWhenNoReadResults()
    {
        $this->addResultFile();
        $this->assertFalse($this->results->getLatestResults());
    }

    public function testWhenReadResults()
    {
        $content = 'wibble';
        $this->addResultFileAsRead($content);
        $this->assertEquals($content, $this->results->getLatestResults());
    }

    public function testWhenMultipleReadResults()
    {
        $content = 'wibble';
        $this->addResultFileAsRead('foo');
        $this->addResultFileAsRead('bar');
        $this->addResultFileAsRead($content);
        $this->assertEquals($content, $this->results->getLatestResults());
    }
}
