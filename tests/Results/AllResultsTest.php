<?php

namespace RickSelby\Tests\Results;

class AllResultsTest extends ResultsSetup
{
    public function testWhenNoResults()
    {
        $this->assertEquals([], $this->results->getAllResults());
    }

    public function testWhenNoReadResults()
    {
        $this->addResultFile();
        $this->assertEquals([], $this->results->getAllResults());
    }

    public function testWhenReadResults()
    {
        $content = 'wibble';
        $file = $this->addResultFileAsRead($content);
        $this->assertEquals([$file->getName() => $content], $this->results->getAllResults());
    }

    public function testWhenMultipleReadResults()
    {
        $expected = [];
        foreach(['foo', 'bar', 'baz'] AS $str) {
            $file = $this->addResultFileAsRead($str);
            $expected[$file->getName()] = $str;
        }

        $this->assertEquals($expected, $this->results->getAllResults());
    }
}
