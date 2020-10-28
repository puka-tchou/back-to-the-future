<?php

namespace BackToTheFuture\tests;

use BackToTheFuture\utilities\FilterConnection;
use PHPUnit\Framework\TestCase;

class FilterConnectionTest extends TestCase
{
    public function testReturnsFalseWhenTheMethodIsNotAllowed()
    {
        $filter = new FilterConnection();
        $_SERVER['REQUEST_METHOD'] = "INVALID";

        $this->assertNotTrue($filter->connectionIsAllowed());
    }

    /**
     * @dataProvider methodProvider
     */
    public function testReturnsTrueWhenTheMethodIsAllowed(string $method)
    {
        $filter = new FilterConnection();
        $_SERVER['REQUEST_METHOD'] = $method;

        $this->assertTrue($filter->connectionIsAllowed());
    }

    public function methodProvider(): array
    {
        return [
            ["GET"],
            ["POST"],
            ["HEAD"]
        ];
    }
}
