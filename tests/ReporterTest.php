<?php

namespace BackToTheFuture\tests;

use BackToTheFuture\utilities\Reporter;
use PHPUnit\Framework\TestCase;

class ReporterTest extends TestCase
{
    protected $reporter;
    protected $code = 0;
    protected $wrongCode = 10;
    protected $message = 'Wonderful.';
    protected $body = 'Truly wonderful';
    protected $defaultAnswer;


    protected function setUp(): void
    {
        $this->reporter = new Reporter();
        $this->defaultAnswer = [
            'code' => 5,
            'message' => 'We received an incorrect status code.',
            'body' => [
                'original_code' => $this->wrongCode,
                'original_message' => $this->message,
                'original_body' => $this->body
            ]
        ];
    }

    /**
     * @dataProvider goodCodeProvider
     */
    public function testCorrectlyFormatsAMessage(int $goodCode)
    {
        $expected = array(
            'code' => $goodCode,
            'message' => $this->message,
            'body' => $this->body
        );

        $this->assertSame($expected, $this->reporter->format($goodCode, $this->message, $this->body));
    }

    public function testCodeDefaultsTo5()
    {
        $this->assertSame($this->defaultAnswer, $this->reporter->format($this->wrongCode, $this->message, $this->body));
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendsTheRightOutput()
    {
        $expected = json_encode(array(
            'code' => $this->code,
            'message' => $this->message,
            'body' => $this->body
        ));

        $this->expectOutputString($expected);
        $this->reporter->send($this->code, $this->message, $this->body);
    }


    /**
     * @runInSeparateProcess
     */
    public function testSendsTheDefaultOutput()
    {
        $this->expectOutputString(json_encode($this->defaultAnswer));
        $this->reporter->send($this->wrongCode, $this->message, $this->body);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendsJsonHeader()
    {
        $this->reporter->send($this->code, $this->message, $this->body);
        $headerList = xdebug_get_headers();

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
