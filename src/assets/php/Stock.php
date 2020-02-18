<?php namespace Stock;

require __DIR__ . '/../../../vendor/autoload.php';

use dealers\AlliedElec\AlliedElec;
use utilities\PartList\PartList;

/**
 * Retrieve the current stock informations from online stores.
 */
class Stock
{
    /** Retrieve stock informations for a given part number.
     * @param string $part The part number to test.
     *
     * @return array
     */
    public function get(string $part): array
    {
        $alliedelec = new AlliedElec;
        $stockByPart = array();
        
        $stockByPart = $alliedelec->getStock($part);

        return $stockByPart;
    }
}
