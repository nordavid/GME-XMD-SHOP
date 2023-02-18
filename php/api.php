<?php
require_once('./init.php');
header('Content-Type: application/json; charset=utf-8');

$endpoints = [
    'account/register' => ['handler' => 'registerHandler', 'method' => 'POST'],
    'account/login' => ['handler' => 'loginHandler', 'method' => 'POST'],
    'account/logout' => ['handler' => 'logoutHandler', 'method' => 'POST'],
    'account/player' => ['handler' => 'playerInfoHandler', 'method' => 'POST'],
    'entity/items' => ['handler' => 'entityItemsHandler', 'method' => 'GET'],
    'shop/item' => ['handler' => 'itemInfoHandler', 'method' => 'GET'],
    'shop/item/properties' => ['handler' => 'itemPropsHandler', 'method' => 'GET'],
    'shop/item/buy' => ['handler' => 'itemBuyHandler', 'method' => 'POST'],
    'shop/item/sell' => ['handler' => 'itemSellHandler', 'method' => 'POST'],
    'shop/item/add' => ['handler' => 'itemAddHandler', 'method' => 'POST']
];

$endpoint = ltrim($_SERVER['PATH_INFO'], "/");

if (array_key_exists($endpoint, $endpoints)) {
    $handler = $endpoints[$endpoint]['handler'];
    $method = $endpoints[$endpoint]['method'];
    require_once("./$endpoint.php");

    if ($_SERVER['REQUEST_METHOD'] === $method) {
        $params = ($method === 'GET' ? $_GET : $_POST);
        call_user_func($handler, $params);
    } else {
        http_response_code(405);
        echo errorMsg("Request-Methode nicht erlaubt");
    }
} else {
    http_response_code(404);
    echo errorMsg("Endpoint nicht gefunden");
}
