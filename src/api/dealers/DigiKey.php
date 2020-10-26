<?php

namespace dealers\DigiKey;

use dealers\DealerInterface\DealerInterface;

class DigiKey implements DealerInterface
{
    /** Get stock information from DigiKey.
     *
     * @return string[]
     */
    public function getStock(string $part_number): array
    {
        return array('Implementation may need NodeJS because DigiKey dynamically generates its pages.');
    }
}
