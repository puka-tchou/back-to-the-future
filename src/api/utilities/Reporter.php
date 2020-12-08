<?php

namespace BackToTheFuture\utilities;

/**
 * The Reporter class is responsible for returning data to the client.
 */
class Reporter
{
    /** Send the appropriate answer, accompanied with the correct HTTP headers.
     * The answer will always be a JSON object with these properties:
     * ```json
     * {
     *   "code": 0, //one of the accepted answer codes [0-9]
     *   "message": "Everything went fine.", //a short and informative message
     *   "body": {} //a JSON object
     * }
     * ```
     *
     * @param integer $code The status code. `0` if everything is OK.
     * @param string $shortMessage The message should complement the status code.
     * @param mixed $body The body of the answer.
     *
     * @return void
     */
    public function send(int $code, string $shortMessage, $body): void
    {
        $answer = $this->format($code, $shortMessage, $body);

        header(
            $answer['code'] === 5
                ? 'HTTP/1.1 500 Server error: ' . $answer['message']
                : 'HTTP/1.1 200 OK'
        );
        header('Content-Type: application/json');

        echo json_encode($answer);
    }

    /** Format the answer.
     * The answer will always be an array with these properties:
     * ```json
     * {
     *   "code": 0, //one of the accepted answer codes [0-9]
     *   "message": "Everything went fine.", //a short and informative message
     *   "body": {} //a JSON object
     * }
     * ```
     *
     * @param integer $code The code. `0` if everything is OK.
     * @param string $shortMessage The message should complement the status code.
     * @param mixed $body The body of the answer.
     *
     * @return array
     */
    public function format(int $code, string $shortMessage, $body): array
    {
        if ($code < 0 || $code > 9) {
            $oldBody = $body;
            $body = [
                'original_code' => $code,
                'original_message' => $shortMessage,
                'original_body' => $oldBody
            ];
            $shortMessage = 'We received an incorrect status code.';
            $code = 5;
        }

        return array(
            'code' => $code,
            'message' => $shortMessage,
            'body' => $body
        );
    }
}
