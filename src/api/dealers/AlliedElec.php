<?php namespace dealers\AlliedElec;

use DOMDocument;
use DOMXPath;
use Exception;
/**
 * AlliedElec distributor stock check.
 */
class AlliedElec
{
    /** Check the stock of a given part number.
     * @param string $part_number The part number that you want to check.
     *
     * @return array An array containing the stock, the stock on order and the minimum number of parts to order.
     */
    public function getStock(string $part_number): array
    {
        $ch = curl_init('https://www.alliedelec.com/view/search?keyword='.$part_number);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = str_replace('&', '$&amp;', curl_exec($ch));
        
        // Temporarly mute warnings and errors caused by a malformed HTML
        // This should be safe because DOMXPath will still throw errors
        libxml_use_internal_errors(true);
        $document = new DOMDocument();
        $document->preserveWhiteSpace = false;
        $document->loadHTML($html);
        
        $documentXpath = new DOMXPath($document);
        // This div contains the results of our query
        $details = $documentXpath->query('//div[@class="search-result-details"]');
        $regex = '/\bManufacturer #: '.$part_number.'\b/';
        $parts_in_stock = -1;
        $parts_on_order = -1;
        $parts_min_order = -1;
        // As ther may be multiple results, we need to select only the one
        // that matches exactly the part number.
        foreach ($details as $detail) {
            if (preg_match($regex, $detail->nodeValue)) {
                $xml = simplexml_import_dom($detail);
                break;
            }
        }
        if (!isset($xml)) {
            return array(
                'err' => true,
                'response' => 'Exact part number not found ' . $part_number
            );
        }
        $stocks = $xml->div[1][0]->div[0]->span;
        $stocksCount = count($stocks);
        // We know the structure of the array, so we know that we can test only one element out of two.
        for ($i=0; $i < $stocksCount; $i += 2) {
            switch ($stocks[$i]) {
                case 'In Stock: ':
                    $parts_in_stock = (int)$stocks[$i+1];
                    break;
                case 'On Order: ':
                    $parts_on_order = (int)$stocks[$i+1];
                    break;
                case 'Min Qty: ':
                    $parts_min_order = (int)$stocks[$i+1];
                    break;
                default:
                    throw new Exception('Stock values not found', 1);
            }
        }
        return array(
            'parts_in_stock' => $parts_in_stock,
            'parts_on_order'=>$parts_on_order,
            'parts_min_order'=>$parts_min_order
        );
    }
}
