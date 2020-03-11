<?php namespace dealers\NetComponents;

use dealers\iDealer\iDealer;

class NetComponents
{
    /**
     * @return mixed[]
     */
    public function getStock(): void
    {
        define('BASE_URL', 'https://api.netcomponents.com/api/DILP/v3');
        define('DEFAULT_AUTH', 'Authorization: Basic ' . base64_encode('crouzet:33abd2799589'));
        define('DEFAULT_CONTENT', 'Content-Type:application/json');
        define('LOGIN', 'OAuth');
        if (!isset($_SESSION[LOGIN])) {
            $this->do_api_login();
        }
        die();
    }

    private function do_api_login(): void
    {
        $response = $this->get_api_response('/Login', DEFAULT_AUTH, 'POST', array(DEFAULT_CONTENT), array(), false);
        $login_info = json_decode($response, true);
        $_SESSION[LOGIN] = 'Authorization: '.$login_info['AuthToken'];
        var_dump($response);
    }

    /**
     * @return mixed|string
     */
    private function get_api_response($url, $auth, $method, $headers = array(DEFAULT_CONTENT), $postData = array(), $retryOnFailure = true)
    {
        $process = curl_init(BASE_URL.$url);
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

        $http_status = curl_getinfo($process, CURLINFO_HTTP_CODE);
        $error = curl_error($process);
        $header_size = curl_getinfo($process, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($process);

        // if doesn't return OK header, login and retry
        if ($http_status != 200) {
            if ($http_status == 401 && $retryOnFailure) {
                do_api_login();
                return get_api_response($url, $method, $headers, $_SESSION[LOGIN], $postData, false);
            } else {
                die($header . '<br />' . $error);
            }
        }

        return $body;
    }
}
