<?php namespace data\Stock;

use dealers\AlliedElec\AlliedElec;

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
