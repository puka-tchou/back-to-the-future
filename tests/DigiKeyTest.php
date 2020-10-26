<?php

use dealers\DigiKey\DigiKey;
use PHPUnit\Framework\TestCase;

class DigiKeyTest extends TestCase
{
    public function testGetStockReturnsAnArray()
    {
        $digikey = new DigiKey();
        $expected = array('Implementation may need NodeJS because DigiKey dynamically generates its pages.');


        $this->assertSame($expected, $digikey->getStock(''));
    }
}
