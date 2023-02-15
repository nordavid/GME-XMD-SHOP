<?php
require_once("./init.php");

$entityId = $_POST['entityId'];

$sql = "SELECT item.*, inventory.amount FROM inventory
INNER JOIN entity ON inventory.entity_id = entity.id
INNER JOIN item ON inventory.item_id = item.id
WHERE entity.id = ?;";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $entityId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $myArray[] = $row;
    }
    echo json_encode($myArray);
} else echo json_encode([]);
