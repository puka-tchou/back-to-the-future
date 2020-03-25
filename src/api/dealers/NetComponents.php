<?php namespace dealers\NetComponents;

use dealers\iDealer\iDealer;
use utilities\Reporter\Reporter;

class NetComponents implements iDealer
{
    
    /**
     * @return mixed[]
     */
    public function getStock(string $part): array
    {
        $reporter = new Reporter;
        $code = 0;
        $message = 'Found stock records';
        $body = [];
        $login = parse_ini_file(__DIR__ . '/../netcomponents.ini');
        define('BASE_URL', 'https://api.netcomponents.com/api/DILP/v3');
        define('DEFAULT_AUTH', 'Authorization: Basic ' . base64_encode($login['password']));
        define('DEFAULT_CONTENT', 'Content-Type:application/json');
        define('LOGIN', 'OAuth');

        $login_response = $this->getApiResponse(BASE_URL.'/Login', DEFAULT_AUTH, 'POST', array(DEFAULT_CONTENT), array());
        $token = 'Authorization: ' . json_decode($login_response, true)['AuthToken'];
        $search_response = json_decode(
            $this->getApiResponse(
                BASE_URL.'/Search',
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
        foreach ($filteredResponse as $value) {
            $body[$value->Distributor->Name] = $value->Quantity;
        }

        return $reporter->format(
            $code,
            $message,
            $body
        );
    }

    /**
     * @return mixed|string
     */
    private function getApiResponse($url, $auth, $method, $headers, $postData): string
    {
        if (count($postData)>0) {
            $url .= '?';
            foreach ($postData as $key => $value) {
                $url .= $key.'='.$value.'&';
            }
        }

        $process = curl_init($url);
        $hdrs    = $headers;
        $hdrs[]  = $auth;

        curl_setopt($process, CURLOPT_HTTPHEADER, $hdrs);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_VERBOSE, 1);
        curl_setopt($process, CURLOPT_HEADER, 1);
    
        if ($method === 'POST') {
            curl_setopt($process, CURLOPT_POST, 1);
            curl_setopt($process, CURLOPT_POSTFIELDS, $postData);
        } else {
            curl_setopt($process, CURLOPT_POST, false);
        }

        $response = curl_exec($process);
        $header_size = curl_getinfo($process, CURLINFO_HEADER_SIZE);
        $body = substr($response, $header_size);

        curl_close($process);

        return $body;
    }
}
