<?php
require_once('./util/file_upload.php');

function itemAddHandler($params)
{
    global $conn;

    // Get next item id for image upload
    $itemId = 1;
    $stmt = $conn->query("SHOW TABLE STATUS LIKE 'item'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
        $stmt->bindParam(':name', $params['itemname'], PDO::PARAM_STR);
        $stmt->bindParam(':rarity', $params['rarity'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $params['description'], PDO::PARAM_STR);
        $stmt->bindParam(':cost', $params['cost'], PDO::PARAM_INT);
        $stmt->bindParam(':image', $imgPath, PDO::PARAM_STR);
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
            $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);
            $stmt->bindParam(':name', $property_name, PDO::PARAM_STR);
            $stmt->bindParam(':propType', $property_type, PDO::PARAM_STR);
            $stmt->bindParam(':statType', $stat_type, PDO::PARAM_STR);
            $stmt->bindParam(':value', $value, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    return true;
}
