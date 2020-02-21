<?php

require __DIR__ . '/../../vendor/autoload.php';

use route\Route\Route;

filterConnection();
route();

/** Filter connection method and `die();` if the method is not allowed.
 * @return void
 */
function filterConnection()
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
function route()
{
    header('Content-Type: application/json');
    
    $url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '/';
    $route = new Route;
    
    switch ($url) {
        case '/api/add':
            echo $route->add();
            break;
        case '/api/part':
            echo $route->part();
            break;
        case '/api/parts':
            echo $route->parts();
            break;
        case '/api/products':
            echo $route->products();
            break;
        case '/api/update':
            echo $route->update();
            break;
        case '/api/coffee':
            header("HTTP/1.1 418 I'm a teapot");
            $quote = json_decode(file_get_contents('https://programming-quotes-api.herokuapp.com/quotes/random'));
            echo json_encode(array(
                '☕' => $quote->en . ' (' . $quote->author . ')'
            ));
            break;
        default:
            echo $route->documentation($url);
            break;
    }
}
