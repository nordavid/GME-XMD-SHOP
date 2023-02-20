<?php
require_once('./util/file_upload.php');

function itemAddHandler($itemname, $category, $rarity, $description, $cost, $prop_names, $prop_types, $prop_stat_types, $prop_values)
{
    global $conn;

    // Insert the item into the database
    try {
        $sql = "INSERT INTO item (name, category, rarity, description, cost) VALUES (:name, :category, :rarity, :description, :cost)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $itemname, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':rarity', $rarity, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':cost', $cost, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die(errorMsg("Fehler bei dem Versuch Item hinzuzufügen: " . $e->getMessage()));
    }

    $insertedItemId = $conn->lastInsertId();

    // Add item properties to item
    $itemProps = array();
    for ($i = 0; $i < count($prop_names); $i++) {
        $itemProps[$i]['name'] = $prop_names[$i];
        $itemProps[$i]['propType'] = $prop_types[$i];
        $itemProps[$i]['statType'] = $prop_stat_types[$i];
        $itemProps[$i]['value'] = $prop_values[$i];
    }

    if (!addPropsToItem($insertedItemId, $itemProps)) {
        die(errorMsg("Fehler beim Versuch Item Properties hinzuzufügen"));
    }

    // Upload image for item and update db entry
    uploadItemImg($insertedItemId);


    // Add item to shop inventory
    try {
        $sql = "INSERT INTO inventory (entity_id, item_id) VALUES ((SELECT entity_id FROM shop WHERE category = :category), :itemId)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':itemId', $insertedItemId, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die(errorMsg("Fehler bei dem Versuch Item dem Inventar hinzuzufügen: " . $e->getMessage()));
    }

    exit(successMsg("Item erfolgreich hinzugefügt"));
}

function uploadItemImg($itemId)
{
    global $conn;

    try {
        $imgPath = uploadFile("image", "img_item", getItemImgName($itemId), 5);
        $stm = $conn->prepare('UPDATE item SET image = :imgPath WHERE id = :itemId;');
        $stm->bindParam(":imgPath", $imgPath, PDO::PARAM_STR);
        $stm->bindParam(":itemId", $itemId, PDO::PARAM_STR);
        $stm->execute();
    } catch (Exception $e) {
        die(errorMsg($e->getMessage()));
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
