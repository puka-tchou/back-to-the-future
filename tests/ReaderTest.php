<?php

use PHPUnit\Framework\TestCase;
use utilities\Reader\Reader;

class ReaderTest extends TestCase
{
    public function testErrorsIfPathIsIncorrect()
    {
        $reader = new Reader();
        $path = 'not/a/path';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage("File '" . $path . "' does not seem to exist.");

        $reader->readCSVFile($path);
    }

    public function testCanReadACsv()
    {
        $reader = new Reader();
        $expected = array('This' => 'This' , 'is' => 'is', 'a' => 'a', 'test'=> 'test');

        $actual = $reader->readCSVFile('./tests/sample.csv');

        $this->assertEquals($expected, $actual);
    }
}
