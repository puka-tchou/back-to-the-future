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
        $stockByPart = array(
            'part_number' => $part,
            'date_checked' => date('Y-m-d'),
        );
        
        $stockByPart['stock'] = $alliedelec->getStock($part);
        $stockByPart['supplier'] = 'alliedelec';

        return $stockByPart;
    }
}
