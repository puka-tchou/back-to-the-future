<?php namespace dealers\NetComponents;

use dealers\iDealer\iDealer;

class NetComponents implements iDealer
{
    /**
     * @return mixed[]
     */
    public function getStock(string $part_number): array
    {
        $distributor_id = $_GET['id'];
        $distributor_id_short = '';

        define('DEFAULT_URL', 'https://api.netcomponents.com/api/DILP/v3');
        define('DEFAULT_AUTH', 'Authorization: Basic ' . base64_encode('crouzet:33abd2799589'));
        define('DEFAULT_CONTENT', 'Content-Type:application/json');
        
        if (!isset($_SESSION['OAuth'])) {
            do_api_login();
        }

        function do_api_login()
        {
            $return = get_api_response('/Login?TTL=900', 'POST', array(DEFAULT_CONTENT), DEFAULT_AUTH, array(), false); // do not retry if failed
            $login_info = json_decode($return, true);
            $_SESSION['OAuth'] = 'Authorization: '.$login_info['AuthToken'];
        }

        function get_api_response($url, $method = 'POST', $headers = array(DEFAULT_CONTENT), $auth, $postData = array(), $retryAfterLogin = true)
        {
            $process = curl_init(DEFAULT_URL.$url);
            $hdrs    = $headers;
            $hdrs[]  = $auth;

            curl_setopt($process, CURLOPT_HTTPHEADER, $hdrs);
            curl_setopt($process, CURLOPT_TIMEOUT, 30);
    
            curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($process, CURLOPT_VERBOSE, 1);
            curl_setopt($process, CURLOPT_HEADER, 1);
    
            if ($method == 'POST') {
                curl_setopt($process, CURLOPT_POST, 1);
                curl_setopt($process, CURLOPT_POSTFIELDS, $postData);
            } else {
                curl_setopt($process, CURLOPT_POST, false);
            }
            curl_setopt($process, CURLOPT_RETURNTRANSFER, true);

            $return = curl_exec($process);

            $http_status = curl_getinfo($process, CURLINFO_HTTP_CODE);
            $error = curl_error($process);
            $header_size = curl_getinfo($process, CURLINFO_HEADER_SIZE);
            $header = substr($return, 0, $header_size);
            $body = substr($return, $header_size);

            curl_close($process);

            // if doesn't return OK header, login and retry
            if ($http_status != 200) {
                if ($http_status == 401 && $retryAfterLogin) {
                    do_api_login();
                    return get_api_response($url, $method, $headers, $_SESSION['OAuth'], $postData, false);
                } else {
                    die($header . '<br />' . $error);
                }
            }

            return $body;
        }

        return array();
    }
}
