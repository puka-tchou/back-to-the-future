<?php

use PHPUnit\Framework\TestCase;
use utilities\Reporter\Reporter;

class ReporterTest extends TestCase
{
    protected $reporter;

    protected function setUp(): void
    {
        $this->reporter = new Reporter();
    }

    public function testCanSetShortMessage()
    {
        $message = 'This is a test.';
        $this->reporter->setShortMessage($message);

        $this->assertSame($message, $this->reporter->getShortMessage());
    }

    public function testCanSetCodeToZero()
    {
        $code = 0;
        $this->reporter->setCode($code);

        $this->assertSame($code, $this->reporter->getCode());
    }

    public function testCanSetBody()
    {
        $body = 'This is the body of my request';
        $this->reporter->setBody($body);

        $this->assertSame($body, $this->reporter->getBody());
    }

    public function testCodeDefaultsToFive()
    {
        $code = 10;
        $this->reporter->setCode($code);

        $this->assertSame(5, $this->reporter->getCode());
    }

    public function testCorrectlyFormatsAMessage()
    {
        $code = 0;
        $shortMessage = 'Wonderful.';
        $body = 'Truly wonderful';
        $expected = array(
          'code' => $code,
          'message' => $shortMessage,
          'body' => $body
        );

        $this->assertSame($expected, $this->reporter->format($code, $shortMessage, $body));
    }

    /**
     * @runInSeparateProcess
     */
    public function testSendsTheRightOutput()
    {
        $code = 0;
        $shortMessage = 'Wonderful.';
        $body = 'Truly wonderful';
        $expected = json_encode(array(
          'code' => $code,
          'message' => $shortMessage,
          'body' => $body
        ));

        $this->expectOutputString($expected);
        $this->reporter->send($code, $shortMessage, $body);
    }
}
