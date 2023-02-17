<?php
require_once('./init.php');
header('Content-Type: application/json; charset=utf-8');

//$json = file_get_contents('php://input');
//$parameters = json_decode($json, true);

$endpoints = [
    'account/register' => ['handler' => 'registerHandler', 'method' => 'POST'],
    'account/login' => ['handler' => 'loginHandler', 'method' => 'POST'],
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

function itemAddHandler($params)
{
    require_once('./util/file_upload.php');
    global $conn;

    // Get the form data
    $item_name = $_POST['itemname'];
    $rarity = $_POST['rarity'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];

    $image = $_FILES['image']['name'];

    $property_names = $_POST['property_name'];
    $property_types = $_POST['property_type'];
    $stat_types = $_POST['stat_type'];
    $values = $_POST['value'];

    $itemProps = array();

    for ($i = 0; $i < count($params['property_name']); $i++) {
        $itemProps[$i]['name'] = $property_names[$i];
        $itemProps[$i]['propType'] = $property_types[$i];
        $itemProps[$i]['statType'] = $stat_types[$i];
        $itemProps[$i]['value'] = $values[$i];
    }

    $itemId = 1;
    $result = $conn->query("SHOW TABLE STATUS LIKE 'item'");
    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $itemId = $row['Auto_increment'];
    }

    try {
        $imgPath = uploadFile("image", "img_item", getItemImgName($itemId), 5);
    } catch (Exception $e) {
        die(errorMsg($e->getMessage()));
    }

    // Insert the item into the database
    try {
        $sql = "INSERT INTO item (name, rarity, description, cost, image) VALUES (:name, :rarity, :description, :cost, :image)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $params['itemname']);
        $stmt->bindParam(':rarity', $params['rarity']);
        $stmt->bindParam(':description', $params['description']);
        $stmt->bindParam(':cost', $params['cost']);
        $stmt->bindParam(':image', $imgPath);
        $stmt->execute();
    } catch (PDOException $e) {
        die(errorMsg("Error inserting item: " . $e->getMessage()));
    }

    $item_id = $conn->lastInsertId();

    $success = addPropsToItem($item_id, $itemProps);

    if ($success) {
        exit(successMsg("Item erfolgreich hinzugefügt"));
    } else {
        die(errorMsg("Fehler beim Versuch Item Properties hinzuzufügen"));
    }
}

function getItemImgName($itemId)
{
    return "item_" . $itemId;
}

function addPropsToItem($itemId, $props)
{
    global $conn;

    for ($i = 0; $i < count($props); $i++) {
        $property_name = $props[$i]['name'];
        $property_type = $props[$i]['propType'];
        $stat_type = $props[$i]['statType'];
        $value = $props[$i]['value'];
        try {
            $stmt = $conn->prepare("INSERT INTO item_property (item_id, name, property_type, stat_type, value) VALUES (:id, :name, :propType, :statType, :value)");
            $stmt->bindParam(':id', $itemId);
            $stmt->bindParam(':name', $property_name);
            $stmt->bindParam(':propType', $property_type);
            $stmt->bindParam(':statType', $stat_type);
            $stmt->bindParam(':value', $value);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    return true;
}
