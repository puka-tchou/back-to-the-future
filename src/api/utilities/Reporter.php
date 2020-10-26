<?php

namespace BackToTheFuture\utilities;

/**
 * The Reporter class is responsible for returning data to the client.
 */
class Reporter
{
    /** This is the code of the answer.
     * It should be one of the following :
     * - 0: OK
     * - 1: Notice (see the message to get additional informations)
     * - 2: Bad request (malformed request)
     * - 3: Not allowed (you can't do that, check your method or endpoint)
     * - 4: Not found
     * - 5: Server error (our bad)
     * - (...)
     * - 9: Unknown error (good luck)
     *
     * @var int The status code.
     */
    private int $code;

    /** This is the body of the answer. It can take any shape.
     * In case of an error, be sure to fill in as much details and helps as possible
     * to help the user to understand what went wrong.
     *
     * @var mixed
     */
    private $body;

    /** This is the message of the answer. It should be short and informative.
     * For example: 'The arguments given to the Reporter are not of the right type. The `code` must an `integer` and the `shortMessage` must be a `string`.'
     * @var string The message.
     */
    private string $shortMessage;

    /** Sets the code of the answer to one of the accepted values.
     *
     * @param integer $code The status code, it can be one of:
     * - 0: OK
     * - 1: Notice (see the message to get additional informations)
     * - 2: Bad request (malformed request)
     * - 3: Not allowed (you can't do that, check your method or endpoint)
     * - 4: Not found
     * - 5: Server error (our bad)
     * - (...)
     * - 9: Unknown error (good luck)
     *
     * @return void
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
        if (0 < $code || $code > 9) {
            $this->code = 5;
        }
    }

    /** Get the code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }


    /** Sets the short message of the answer. The `string` should be less than 150 char.
     *
     * @param string $shortMessage The message of the answer. Should be less than 150 char.
     *
     * @return void
     */
    public function setShortMessage(string $shortMessage): void
    {
        $this->shortMessage = $shortMessage;
    }

    /** Get the short message.
     *
     * @return string
     */
    public function getShortMessage(): string
    {
        return $this->shortMessage;
    }

    /** Sets the body of the answer.
     *
     * @param mixed $body The body of the anwser.
     *
     * @return void
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /** Get the body.
     *
     * @return [type]
     */
    public function getBody()
    {
        return $this->body;
    }

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
     * @param integer|null $code The status code. `0` if everything is OK.
     * @param string|null $shortMessage The message should complement the status code.
     * @param mixed|null $body The body of the answer.
     *
     * @return void
     */
    public function send(?int $code = null, ?string $shortMessage = null, $body = null): void
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
     * @param integer|null $code The code. `0` if everything is OK.
     * @param string|null $shortMessage The message should complement the status code.
     * @param mixed|null $body The body of the answer.
     *
     * @return array
     */
    public function format(?int $code, ?string $shortMessage, $body): array
    {
        // These lines are used to get default values
        $code = $code === null ? $this->code : $code;
        $shortMessage = $shortMessage === null ? $this->shortMessage : $shortMessage;
        $body = $body === null ? $this->body : $body;

        if (!is_string($shortMessage) || !is_int($code)) {
            $this->setCode(5);
            $this->setShortMessage('The arguments given to the Reporter are not of the right type. The `code` must an `integer` and the `shortMessage` must be a `string`.');
            $this->setBody(array(
                'shortMessage' => $this->shortMessage,
                'code' => $this->code
            ));
        }

        return array(
            'code' => $code,
            'message' => $shortMessage,
            'body' => $body
        );
    }
}
