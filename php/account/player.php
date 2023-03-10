<?php
function playerInfoHandler($id)
{
    try {
        global $conn;
        $sql = "SELECT * FROM player WHERE player.id = :id LIMIT 1;";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            echo returnData($result);
        } else echo errorMsg("Spieler nicht gefunden");
    } catch (PDOException $e) {
        echo errorMsg($e->getMessage());
    }
}
