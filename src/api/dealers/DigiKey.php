<?php namespace dealers\DigiKey;

use dealers\iDealer\iDealer;

class DigiKey implements iDealer
{
    /** Get stock information from DigiKey.
     * @todo Implementation may need NodeJS because DigiKey dynamically generates its pages.
     *
     * @return string[]
     */
    public function getStock(string $part_number): array
    {
        return array('Implementation may need NodeJS because DigiKey dynamically generates its pages.');
    }
}
