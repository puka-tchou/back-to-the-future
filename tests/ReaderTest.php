<?php

use PHPUnit\Framework\TestCase;
use utilities\Reader\Reader;

class ReaderTest extends TestCase
{
    public function testErrorsIfCsvDoesNotExists()
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

    public function testErrorsIfYamlDoesNotExists()
    {
        $reader = new Reader();
        $path = 'not/a/path';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage("File '" . $path . "' does not seem to exist.");

        $reader->readYAMLFile($path);
    }

    public function testCanReadAYamlFile()
    {
        $reader = new Reader();
        $path = './tests/sample.yml';
        $expected = array('This' => ['is', 'a', 'test']);
      
        $actual=$reader->readYAMLFile($path);
      
        $this->assertEquals($expected, $actual);
    }

    public function testCanReadAYamlString()
    {
        $reader = new Reader();
        $string = <<<EOD
        This:
          - is
          - a
          - test
        EOD;
        $expected = array('This' => ['is', 'a', 'test']);

        $actual = $reader->readFromString($string);

        $this->assertEquals($expected, $actual);
    }
}
