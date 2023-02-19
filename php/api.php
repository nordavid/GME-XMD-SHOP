<?php
require_once('./init.php');
header('Content-Type: application/json; charset=utf-8');

$endpoints = [
    'account/register' => ['handler' => 'registerHandler', 'method' => 'POST', 'params' => ['username', 'email', 'password', 'startitems']],
    'account/login' => ['handler' => 'loginHandler', 'method' => 'POST', 'params' => ['username', 'password']],
    'account/logout' => ['handler' => 'logoutHandler', 'method' => 'POST', 'params' => []],
    'account/player' => ['handler' => 'playerInfoHandler', 'method' => 'POST', 'params' => ['id']],
    'entity/items' => ['handler' => 'entityItemsHandler', 'method' => 'GET', 'params' => ['id']],
    'shop/item' => ['handler' => 'itemInfoHandler', 'method' => 'GET', 'params' => ['id']],
    'shop/item/properties' => ['handler' => 'itemPropsHandler', 'method' => 'GET', 'params' => ['id']],
    'shop/item/buy' => ['handler' => 'itemBuyHandler', 'method' => 'POST', 'params' => ['id']],
    'shop/item/sell' => ['handler' => 'itemSellHandler', 'method' => 'POST', 'params' => ['id', 'amount']],
    'shop/item/add' => ['handler' => 'itemAddHandler', 'method' => 'POST', 'params' => ['itemname', 'category', 'rarity', 'description', 'cost', 'prop_names', 'prop_types', 'prop_stat_types', 'prop_values']]
];

$endpoint = ltrim($_SERVER['PATH_INFO'], "/");

if (array_key_exists($endpoint, $endpoints)) {
    $handler = $endpoints[$endpoint]['handler'];
    $method = $endpoints[$endpoint]['method'];
    $params = $endpoints[$endpoint]['params'];

    if ($_SERVER['REQUEST_METHOD'] === $method) {
        $requestParams = ($method === 'POST') ? $_POST : $_GET;
        $args = [];

        foreach ($params as $param) {
            if (isset($requestParams[$param])) {
                $args[] = $requestParams[$param];
            } else {
                // parameter missing = bad request
                http_response_code(400);
                die(errorMsg("Notweniger Parameter fehlt: " . $param));
            }
        }

        require_once("./$endpoint.php");
        call_user_func_array($handler, $args);
    } else {
        http_response_code(405);
        echo errorMsg("Request-Methode nicht erlaubt");
    }
} else {
    http_response_code(404);
    echo errorMsg("Endpoint nicht gefunden");
}
