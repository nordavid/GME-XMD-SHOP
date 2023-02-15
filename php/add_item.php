<?php
header('Content-Type: application/json; charset=utf-8');
require_once('./init.php');
require_once('./util/error_handling.php');
require_once('./file_upload.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $itemId = 1;
    $result = $conn->query("SHOW TABLE STATUS LIKE 'item'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $itemId = $row['Auto_increment'];
    }

    try {
        $itemPath = uploadFile("image", "img_item", getItemImgName($itemId), 5);
    } catch (Exception $e) {
        die(errorMsg($e->getMessage()));
    }

    // Insert the item into the database
    $stmt = $conn->prepare("INSERT INTO item (name, rarity, description, cost, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssis', $item_name, $rarity, $description, $cost, $itemPath);
    $stmt->execute();
    if ($stmt->error) {
        // If the execution failed, return an error response
        die(errorMsg("Error inserting item: " . $stmt->error));
    }

    $item_id = $conn->insert_id;

    // Insert the item properties into the database
    for ($i = 0; $i < count($property_names); $i++) {
        $property_name = $property_names[$i];
        $property_type = $property_types[$i];
        $stat_type = $stat_types[$i];
        $value = $values[$i];

        $stmt = $conn->prepare("INSERT INTO item_property (item_id, name, property_type, stat_type, value) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('isssi', $item_id, $property_name, $property_type, $stat_type, $value);
        $stmt->execute();
        if ($stmt->error) {
            // If the execution failed, return an error response
            die(errorMsg("Error inserting item property: " . $stmt->error));
        }
    }

    // Close the prepared statements
    $stmt->close();
    //mysqli_stmt_close($propertyStmt);

    // Close the database connection
    $conn->close();

    // Return a success response
    exit(successMsg("Item erfolgreich hinzugefügt"));
}

function getItemImgName($itemId)
{
    return "item_" . $itemId;
}
