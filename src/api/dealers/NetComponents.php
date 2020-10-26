<?php

namespace dealers\NetComponents;

use dealers\DealerInterface\DealerInterface;
use utilities\Reporter\Reporter;

class NetComponents implements DealerInterface
{
    /** Check the stock of a given part number using the netComponents API.
     * @param string $part The part-number to check. It must be on of Crouzet's.
     *
     * @return array A `Reporter` formatted array with the stock information in the body.
     * ```json
     * {
     *  'code': 0,
     *    'message': 'Found stock for 10 dealers.',
     *    'body': {
     *      'Mouser Electronics Inc.': 36,
     *      'Allied Electronics': 50,
     *      'Digi-Key Electronics': 97,
     *      'Distrelec Group AG': 73,
     *      'Electro Sonic Group, Inc.': 8,
     *      'Galco Industrial Electronics': 23,
     *      'Master Electronics': 6,
     *      'Newark, An Avnet Company': 16,
     *      'Sentronic AG': 9,
     *      'OEM Automatic UK': 17
     *     }
     * }
     * ```
     */
    public function getStock(string $part): array
    {
        $reporter = new Reporter();

        $login = parse_ini_file(__DIR__ . '/../netcomponents.ini');
        $defaultAuth = 'Authorization: Basic ' . base64_encode($login['password']);

        $login_response = $this->getApiResponse('/Login', $defaultAuth, 'POST', array('Content-Type:application/json'), array());

        $message = 'There was an error while trying to login to the netcomponents API.';
        $body = $login_response['body'];
        $code = $login_response['code'];

        if ($code === 0) {
            $code = 4;
            $message = 'No records were found with the given part-number.';
            $body = [];

            $token = 'Authorization: ' . json_decode($login_response, true)['AuthToken'];
            $search_response = json_decode(
                $this->getApiResponse(
                    '/Search',
                    $token,
                    'GET',
                    array(),
                    array(
                    'pn1' => $part,
                    'SearchType' => 'EQUALS',
                    'ClientIP' => $_SERVER['SERVER_ADDR']
                    )
                )
            );

            $filteredResponse = $search_response->SearchedParts[0]->Parts;

            if ($filteredResponse !== null) {
                foreach ($filteredResponse as $value) {
                    $body[$value->Distributor->Name] = $value->Quantity;
                }

                $code = 0;
                $message = 'Found stock for ' . count($body) . ' dealers.';
            }
        }

        return $reporter->format(
            $code,
            $message,
            $body
        );
    }

    /** Get an answer from the API.
     * @param mixed $url The final part of the URL for the query. eg: `/Login`.
     * @param mixed $auth The authentication method.
     * @param mixed $method The method for the connection. eg: `POST`. If the
     * answer is `"Main Server: Failed"`, the method is not right.
     * @param mixed $headers The headers.
     * @param mixed $postData The data to post.
     *
     * @return array
     */
    private function getApiResponse($url, $auth, $method, $headers, $postData): array
    {
        $res = array();
        $res['code'] = 0;
        $baseUrl = 'https://api.netcomponents.com/api/DILP/v3' . $url;

        if (count($postData) > 0) {
            $baseUrl .= '?';
            foreach ($postData as $key => $value) {
                $baseUrl .= $key . '=' . $value . '&';
            }
        }

        $process = curl_init($baseUrl);
        $hdrs    = $headers;
        $hdrs[]  = $auth;

        curl_setopt($process, CURLOPT_HTTPHEADER, $hdrs);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_VERBOSE, 1);
        curl_setopt($process, CURLOPT_HEADER, 1);
        curl_setopt($process, CURLOPT_FAILONERROR, 1);

        if ($method === 'POST') {
            curl_setopt($process, CURLOPT_POST, 1);
            curl_setopt($process, CURLOPT_POSTFIELDS, $postData);
        } else {
            curl_setopt($process, CURLOPT_POST, false);
        }

        $response = curl_exec($process);
        $header_size = curl_getinfo($process, CURLINFO_HEADER_SIZE);
        $res['body'] = substr($response, $header_size);

        if (curl_errno($process)) {
            $res['code'] = 5;
            $res['body'] = curl_error($process);
        }

        curl_close($process);

        return $res;
    }
}
