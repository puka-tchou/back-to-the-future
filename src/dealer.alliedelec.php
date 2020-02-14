<?php
require_once __DIR__ . '/../vendor/autoload.php';

$part_number = 'CWD4850';

$ch = curl_init('https://www.alliedelec.com/view/search?keyword='.$part_number);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = str_replace('&', '$&amp;', curl_exec($ch));

$document = new DOMDocument();
$document->preserveWhiteSpace = false;
// Temporarly mute warnings and errors caused by a malformed HTML
// This should be safe because DOMXPath will still throw errors
libxml_use_internal_errors(true);

if ($document->loadHTML($html)) {
    $documentXpath = new DOMXPath($document);
    // This div contains the results of our query
    $details = $documentXpath->query('//div[@class="search-result-details"]');
    $regex = '/\bManufacturer #: '.$part_number.'\b/';
    $xml;
    $parts_in_stock = -1;
    $parts_on_order = -1;
    $parts_min_order = -1;
    // As ther may be multiple results, we need to select only the one
    // that matches exactly the part number.
    foreach ($details as $detail => $value) {
        if (preg_match($regex, $value->nodeValue)) {
            $xml = simplexml_import_dom($value);
            break;
        }
    }
    if (isset($xml)) {
        $stocks = $xml->div[1][0]->div[0]->span;
        // We know the structure of the array, so we know that we can test only one element out of two.
        for ($i=0; $i < count($stocks); $i = $i+2) {
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
                    break;
            }
        }
        var_dump($parts_in_stock . ' ' . $parts_on_order . ' ' . $parts_min_order);
    } else {
        throw new Exception('Exact part number not found');
    }
}
