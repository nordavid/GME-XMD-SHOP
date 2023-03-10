<?php
function itemPropsHandler($itemId)
{
    global $conn;
    try {
        $sql = "SELECT item_property.* FROM item_property
                INNER JOIN item ON item.id = item_property.item_id
                WHERE item.id = :id;";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $itemId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo returnData($result);
        } else {
            echo errorMsg("Keine Properties für Item-ID: $itemId gefunden");
        }
    } catch (PDOException $e) {
        echo errorMsg($e->getMessage());
    }
}
