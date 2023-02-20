<?php
function itemInfoHandler($itemId)
{
    global $conn;
    try {
        $sql = "SELECT item.* FROM item WHERE item.id = :id;";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $itemId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo returnData($result);
        } else {
            echo errorMsg("Kein Item mit ID: $itemId gefunden");
        }
    } catch (PDOException $e) {
        echo errorMsg($e->getMessage());
    }
}
