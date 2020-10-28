<?php

namespace BackToTheFuture\tests;

use BackToTheFuture\utilities\Reporter;
use PHPUnit\Framework\TestCase;

class ReporterTest extends TestCase
{
    protected $reporter;
    protected $code = 0;
    protected $wrongCode = 10;
    protected $shortMessage = 'Wonderful.';
    protected $body = 'Truly wonderful';


    protected function setUp(): void
    {
        $this->reporter = new Reporter();
    }

    /**
     * @dataProvider goodCodeProvider
     */
    public function testCorrectlyFormatsAMessage(int $goodCode)
    {
        $expected = array(
            'code' => $goodCode,
            'message' => $this->shortMessage,
            'body' => $this->body
        );

        $this->assertSame($expected, $this->reporter->format($goodCode, $this->shortMessage, $this->body));
    }

    public function testCodeDefaultsTo5()
    {
        $expected = array(
            'code' => 5,
            'message' => $this->shortMessage,
            'body' => $this->body
        );

        $this->assertSame($expected, $this->reporter->format($this->wrongCode, $this->shortMessage, $this->body));
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendsTheRightOutput()
    {
        $expected = json_encode(array(
            'code' => $this->code,
            'message' => $this->shortMessage,
            'body' => $this->body
        ));

        $this->expectOutputString($expected);
        $this->reporter->send($this->code, $this->shortMessage, $this->body);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendsJsonHeader()
    {
        $this->reporter->send($this->code, $this->shortMessage, $this->body);
        $headerList = xdebug_get_headers();

        echo json_encode($headerList);

        $this->assertContains('Content-Type: application/json', $headerList);
    }

    public function goodCodeProvider(): array
    {
        return [
            [0],
            [1],
            [2],
            [3],
            [4],
            [5],
            [6],
            [7],
            [8],
            [9]
        ];
    }
}
