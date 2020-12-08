<?php

namespace BackToTheFuture\tests;

use BackToTheFuture\data\Database;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DatabaseTest extends TestCase
{
    public function testThrowsWhenTheDatabaseIsOffline()
    {
        $this->expectedCode = 5;
        $this->expectedMessage = 'Could not create a connection to the database.';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionCode($this->expectedCode);
        $this->expectExceptionMessage($this->expectedMessage);

        $this->db = new Database();
    }
}
