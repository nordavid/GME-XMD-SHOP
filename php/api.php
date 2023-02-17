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

function registerHandler($params)
{
    echo json_encode($params);
}

function playerInfoHandler($params)
{
    try {
        global $conn;
        $sql = "SELECT * FROM player WHERE player.id = :id LIMIT 1;";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $params['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            echo returnData($result);
        } else errorMsg("Spieler nicht gefunden", 1);
    } catch (PDOException $e) {
        echo errorMsg($e->getMessage());
    }
}

function entityItemsHandler($params)
{
    try {
        global $conn;

        $sql = "SELECT item.*, inventory.amount FROM inventory
                INNER JOIN entity ON inventory.entity_id = entity.id
                INNER JOIN item ON inventory.item_id = item.id
                WHERE entity.id = :id;";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $params["id"]);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo returnData($result);
        } else {
            $id = $params["id"];
            echo errorMsg("Keine Items für ID: $id gefunden");
        }
    } catch (PDOException $e) {
        echo errorMsg($e->getMessage());
    }
}

function itemPropsHandler($params)
{
    try {
        global $conn;

        $sql = "SELECT item_property.* FROM item_property
                INNER JOIN item ON item.id = item_property.item_id
                WHERE item.id = :id;";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $params["id"]);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo returnData($result);
        } else {
            $id = $params["id"];
            echo errorMsg("Keine Properties für Item-ID: $id gefunden");
        }
    } catch (PDOException $e) {
        echo errorMsg($e->getMessage());
    }
}
