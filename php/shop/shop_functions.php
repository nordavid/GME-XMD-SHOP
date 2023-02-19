<?php
enum TransferType
{
    case ToPlayer;
    case ToShop;
}

function transferItems($fromEntId, $toEntId, $itemId, $amount, TransferType $type)
{
    global $conn;
    $fromInvAmount = getInvAmount($fromEntId, $itemId);

    // check if seller has enough items to transfer
    if ($fromInvAmount - $amount >= 0) {
        // remove items from seller
        updateInvAmount($fromEntId, $itemId, -$amount);

        // check if reciever has item already in inventory
        $stmt = $conn->prepare('SELECT amount FROM inventory WHERE entity_id = :entityId AND item_id = :itemId;');
        $stmt->bindParam(":entityId", $toEntId, PDO::PARAM_INT);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        if (!$stmt->execute()) {
            throw new Exception("Fehler bei Inventar-Abfrage");
        }

        if ($stmt->rowCount() > 0) {
            // reciever has item already in inventory
            updateInvAmount($toEntId, $itemId, $amount);
        } else {
            // add item to inventory
            addItemsToInv($toEntId, $itemId, $amount);
        }

        // remove item from player inv if amount = 0
        if ($type == TransferType::ToShop && $fromInvAmount - $amount == 0) {
            deleteItemFromInv($fromEntId, $itemId);
        }
    } else {
        throw new Exception("Nicht genug Items im Inventar");
    }
}

function addItemsToInv($entityId, $itemId, $amount)
{
    global $conn;
    try {
        $sql = "INSERT INTO inventory (entity_id, item_id, amount) VALUES (:entityId, :itemId, :amount)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':entityId', $entityId, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die(errorMsg("Fehler: " . $e->getMessage()));
    }
}

function updateInvAmount($entityId, $itemId, $amount)
{
    global $conn;
    try {
        $stmt = $conn->prepare('UPDATE inventory SET amount = amount + :amount WHERE entity_id = :entityId AND item_id = :itemId;');
        $stmt->bindParam(":amount", $amount, PDO::PARAM_INT);
        $stmt->bindParam(":entityId", $entityId, PDO::PARAM_INT);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die(errorMsg($e->getMessage()));
    }
}

function deleteItemFromInv($entityId, $itemId)
{
    global $conn;
    try {
        $stmt = $conn->prepare('DELETE FROM inventory WHERE entity_id = :entityId AND item_id = :itemId;');
        $stmt->bindParam(":entityId", $entityId, PDO::PARAM_INT);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die(errorMsg($e->getMessage()));
    }
}

function getBalance($playerId)
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT player.balance FROM player WHERE player.id = :id;");
        $stmt->bindParam(":id", $playerId, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['balance'];
        } else {
            throw new PDOException("Spieler wurde nicht gefunden");
        }
    } catch (PDOException $e) {
        echo errorMsg($e->getMessage());
    }
    return false;
}

function updateBalance($playerId, $balance)
{
    global $conn;
    try {
        $stmt = $conn->prepare('UPDATE player SET balance = balance + :balance WHERE id = :playerId;');
        $stmt->bindParam(":balance", $balance, PDO::PARAM_INT);
        $stmt->bindParam(":playerId", $playerId, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        die(errorMsg($e->getMessage()));
    }
}

function getItemCost($itemId)
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT item.cost FROM item WHERE item.id = :id;");
        $stmt->bindParam(":id", $itemId, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['cost'];
        } else {
            throw new PDOException("Item konnte nicht gefunden werden");
        }
    } catch (PDOException $e) {
        die(errorMsg($e->getMessage()));
    }
    return false;
}

function getInvAmount($entityId, $itemId)
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT inventory.amount FROM inventory WHERE item_id = :id AND entity_id = :entityId;");
        $stmt->bindParam(":id", $itemId, PDO::PARAM_INT);
        $stmt->bindParam(":entityId", $entityId, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['amount'];
        } else {
            throw new PDOException("Item nicht im Inventar");
        }
    } catch (PDOException $e) {
        echo errorMsg($e->getMessage());
    }
    return 0;
}
