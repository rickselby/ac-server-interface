<?php

namespace RickSelby\Tests\Results;

class LatestResultsTest extends ResultsSetup
{
    public function testWhenNoResults()
    {
        $this->assertFalse($this->results->getLatestResults());
    }

    public function testWhenOneResults()
    {
        $content = 'wibble';
        $this->addResultFile($content);
        $this->assertEquals($content, $this->results->getLatestResults());
    }

    public function testWhenMultipleResults()
    {
        $content = 'wibble';
        $this->addResultFile('foo');
        $this->addResultFile('bar');
        $this->addResultFile($content);
        $this->assertEquals($content, $this->results->getLatestResults());
    }
}
