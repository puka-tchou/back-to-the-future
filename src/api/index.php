<?php

require __DIR__ . '/../../vendor/autoload.php';

use route\Route\Route;

filter_connection();
route();

/** Filter connection method and `die();` if the method is not allowed.
 * @return void
 */
function filter_connection(): void
{
    $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'INVALID';

    if ($method !== 'GET'
        && $method !== 'HEAD'
        && $method !== 'POST'
    ) {
        header('HTTP/1.1 405 Method Not Allowed');
        die();
    }
}

/** Routing.
 * @return void
 */
function route(): void
{
    $url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '/';
    $route = new Route;
    
    switch ($url) {
        case '/api/add':
            $route->add();
            break;
        case '/api/part':
            $route->part();
            break;
        case '/api/parts':
            $route->parts();
            break;
        case '/api/products':
            $route->products();
            break;
        case '/api/update':
            $route->update();
            break;
        case '/api/coffee':
            header("HTTP/1.1 418 I'm a teapot");
            $quote = json_decode(file_get_contents('https://programming-quotes-api.herokuapp.com/quotes/random'));
            echo json_encode(array(
                '☕' => $quote->en . ' (' . $quote->author . ')'
            ));
            break;
        default:
            $route->documentation($url);
            break;
    }
}
