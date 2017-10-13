<?php

namespace RickSelby\Tests\Results;

class AllResultsTest extends ResultsSetup
{
    public function testWhenNoResults()
    {
        $this->assertEquals([], $this->results->getAllResults());
    }

    public function testWhenOneResults()
    {
        $content = 'wibble';
        $path = $this->addResultFile($content);
        $this->assertEquals([basename($path) => $content], $this->results->getAllResults());
    }

    public function testWhenMultipleResults()
    {
        $expected = [];
        foreach(['foo', 'bar', 'baz'] AS $str) {
            $path = $this->addResultFile($str);
            $expected[basename($path)] = $str;
        }

        $this->assertEquals($expected, $this->results->getAllResults());
    }
}
