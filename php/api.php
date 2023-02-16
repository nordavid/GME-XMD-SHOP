<?php
require_once('./init.php');
header('Content-Type: application/json');

//$json = file_get_contents('php://input');
//$parameters = json_decode($json, true);

$endpoints = [
    'account/register' => ['handler' => 'registerHandler', 'method' => 'POST'],
    'account/login' => ['handler' => 'loginHandler', 'method' => 'POST'],
    'player/info' => ['handler' => 'playerInfoHandler', 'method' => 'GET'],
    'entity/items' => ['handler' => 'entityItemsHandler', 'method' => 'GET'],
    'shop/item' => ['handler' => 'itemGetHandler', 'method' => 'GET'],
    'shop/item/buy' => ['handler' => 'itemBuyHandler', 'method' => 'POST'],
    'shop/item/sell' => ['handler' => 'itemSellHandler', 'method' => 'POST'],
    'shop/item/add' => ['handler' => 'itemAddHandler', 'method' => 'POST']
];

$endpoint = ltrim($_SERVER['PATH_INFO'], "/");

if (array_key_exists($endpoint, $endpoints)) {
    $handler = $endpoints[$endpoint]['handler'];
    $method = $endpoints[$endpoint]['method'];

    if ($_SERVER['REQUEST_METHOD'] === $method) {
        $params = ($method === 'GET' ? $_GET : $_POST);
        call_user_func($handler, $params);
    } else {
        http_response_code(405);
        echo errorMsg("Request-Methode nicht erlaubt");
    }
} else {
    http_response_code(404);
}

function registerHandler($params)
{
    echo json_encode($params);
}

function playerInfoHandler($params)
{
    global $conn;
    $sql = "SELECT * FROM player WHERE player.id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $params['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return json_encode($result->fetch_assoc());
    } else return [];
}

function getPlayer($id)
{
    global $conn;
    $sql = "SELECT * FROM player WHERE player.id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else return null;
}
