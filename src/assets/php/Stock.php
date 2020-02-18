<?php namespace Stock;

require __DIR__ . '/../../../vendor/autoload.php';

use dealers\AlliedElec\AlliedElec;
use utilities\PartList\PartList;

/**
 * Retrieve the current stock informations from online stores.
 */
class Stock
{
    /**
     * @param string $path The path to a file containing a list of part numbers.
     *
     * @return array
     */
    public function get(string $path): array
    {
        $set = new PartList;
        $alliedelec = new AlliedElec;
        $stockByPart = array();
        
        $parts = $set->readFromFile($path);
        foreach ($parts as $part) {
            $stockByPart[$part] = $alliedelec->getStock($part);
        }
        return $stockByPart;
    }
}
