<?php namespace utilities\Reporter;

/**
 * The Reporter class is responsible for returning data to the client.
 */
class Reporter
{
    /** Format and send the appropriate answer, accompanied with the correct HTTP headers.
     * @param mixed $body The body of the answer.
     * @param string $message The message should complement the status code.
     * @param integer $code The code. `0` if everything is OK.
     *
     * @return void
     */
    public function send($body, string $message = 'Everything went fine.', int $code = 0): array
    {
        if (!is_string($message)||
            !is_int($code)
            ) {
            $code = 5;
            $message = 'The arguments given to the Reporter are not of the right type.';
        }
        
        $message = array(
            'code' => $code,
            'message' => $message,
            'body'=> $body
        );
        $http_code = ($code === 5) ? 'HTTP/1.1 500 Server error: ' . $message : 'HTTP/1.1 200 OK';
        header($http_code);
        header('Content-Type: application/json');
        echo json_encode($message);

        return $message;
    }

    public function format($body, string $message = 'Everything went fine.', int $code = 0): array
    {
        if (!is_string($message)||
            !is_int($code)
            ) {
            $code = 5;
            $message = 'The arguments given to the Reporter are not of the right type.';
        }
        
        return array(
            'code' => $code,
            'message' => $message,
            'body'=> $body
        );
    }
}
