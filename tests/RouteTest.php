<?php

use PHPUnit\Framework\TestCase;
use route\Route\Route;

class RouteTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testAddSendsDefaultIfFileIsNull()
    {
        $route = new Route();
        $code = 2;
        $shortMessage = 'The CSV file was not found.';
        $body[] =  'The CSV file containing the part-numbers was not found in your request. Please, make sure that you are sending a "multipart/form-data" request with a "parts" field containing the CSV file. The "parts" field should be of type "file".';
        $expected = array(
        'code' => $code,
        'message' => $shortMessage,
        'body' => $body
        );

        $this->expectOutputString(json_encode($expected));
        $route->add();
    }
}
