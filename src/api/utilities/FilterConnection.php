<?php

namespace BackToTheFuture\utilities;

class FilterConnection
{
  /** Filter connection method.
   * @return bool
   */
    public function connectionIsAllowed(): bool
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'INVALID';
        $result = false;

        if (
            $method === 'GET'
            || $method === 'HEAD'
            || $method === 'POST'
        ) {
            $result = true;
        }

        return $result;
    }
}
