<?php
require_once('./util/file_upload.php');

function itemAddHandler($params)
{
    global $conn;

    // Get next item id for image upload
    $itemId = 1;
    $result = $conn->query("SHOW TABLE STATUS LIKE 'item'");
    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $itemId = $row['Auto_increment'];
    }

    $imgPath = "img_item/default.png";
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
        die(errorMsg("Fehler bei dem Versuch Item hinzuzufügen: " . $e->getMessage()));
    }

    // Add item properties to item
    $itemProps = buildItemPropsArr($params);
    if (!addPropsToItem($itemId, $itemProps)) {
        die(errorMsg("Fehler beim Versuch Item Properties hinzuzufügen"));
    }

    exit(successMsg("Item erfolgreich hinzugefügt"));
}

function buildItemPropsArr($params)
{
    $itemProps = array();
    for ($i = 0; $i < count($params['property_name']); $i++) {
        $itemProps[$i]['name'] = $params['property_name'][$i];
        $itemProps[$i]['propType'] = $params['property_type'][$i];
        $itemProps[$i]['statType'] = $params['stat_type'][$i];
        $itemProps[$i]['value'] = $params['value'][$i];
    }
    return $itemProps;
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
