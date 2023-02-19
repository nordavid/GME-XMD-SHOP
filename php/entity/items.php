<?php
function entityItemsHandler($entityId)
{
    global $conn;
    try {

        $sql = "SELECT item.*, inventory.amount FROM inventory
                INNER JOIN entity ON inventory.entity_id = entity.id
                INNER JOIN item ON inventory.item_id = item.id
                WHERE entity.id = :id;";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $entityId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo returnData($result);
        } else {
            echo errorMsg("Keine Items für Entity ID: $entityId gefunden");
        }
    } catch (PDOException $e) {
        echo errorMsg($e->getMessage());
    }
}
