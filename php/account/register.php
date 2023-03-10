<?php
require_once('./util/input_validation.php');

function registerHandler($username, $email, $password, $startitems)
{
    global $conn;

    if (!isValidUsername($username)) {
        die(errorMsg("Username hat falsches Format"));
    }

    if (!isValidEmail($email)) {
        die(errorMsg("E-Mail Adresse hat falsches Format"));
    }

    if (!isValidPassword($password, 6, 30)) {
        die(errorMsg("Das Passwort muss zwischen 6 und 30 Zeichen lang sein"));
    }

    if (isUsernameUsed($username)) {
        die(errorMsg("Username bereits vergeben"));
    }

    if (isEmailUsed($email)) {
        die(errorMsg("E-Mail Adresse wird bereits verwendet"));
    }

    $entityType = "Player";
    try {
        $stmt = $conn->prepare("INSERT INTO entity (type) VALUES (:entityType)");
        $stmt->bindParam(":entityType", $entityType, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        die(errorMsg());
    }

    try {
        $entityId = $conn->lastInsertId();
        $password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO player (entity_id, username, password, email) VALUES (:entityId, :username, :password, :email)");
        $stmt->bindParam(":entityId", $entityId, PDO::PARAM_STR);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $playerId = $conn->lastInsertId();

        if ($startitems == "true") {
            givePlayerRandomItems($entityId, START_ITEM_AMOUNT);
        }

        $_SESSION['isLoggedIn'] = true;
        $_SESSION['playerEntId'] = $entityId;
        $_SESSION['playerId'] = $playerId;
        $_SESSION['isAdmin'] = false;
        exit(successMsg("Erfolgreich registriert"));
    } catch (PDOException $e) {
        die(errorMsg("Fehler bei Registrierung. " . $e->getMessage()));
    }
}

function givePlayerRandomItems($entityId, $numOfItems)
{
    global $conn;

    try {
        $stmt = $conn->prepare("SELECT id FROM item ORDER BY RAND() LIMIT :numOfItems");
        $stmt->bindParam(":numOfItems", $numOfItems, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die(errorMsg($e->getMessage()));
    }

    $amount = 1;
    for ($i = 0; $i < $numOfItems; $i++) {
        try {
            $stmt = $conn->prepare("INSERT INTO inventory (entity_id, item_id, amount) VALUES (:entityId, :itemId, :amount)");
            $stmt->bindParam(":entityId", $entityId, PDO::PARAM_INT);
            $stmt->bindParam(":itemId", $result[$i]['id'], PDO::PARAM_INT);
            $stmt->bindParam(":amount", $amount, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            die(errorMsg($e->getMessage()));
        }
    }
}

function isEmailUsed($email)
{
    global $conn;

    try {
        $stmt = $conn->prepare('SELECT id FROM player WHERE email = :email;');
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }
    } catch (PDOException $e) {
        echo errorMsg("Fehler: " . $e->getMessage());
        return true;
    }
    return false;
}

function isUsernameUsed($username)
{
    global $conn;

    try {
        $stmt = $conn->prepare('SELECT id FROM player WHERE username = :username;');
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }
    } catch (PDOException $e) {
        echo errorMsg("Fehler: " . $e->getMessage());
        return true;
    }
    return false;
}
