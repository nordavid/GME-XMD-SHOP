<?php
require_once('./util/input_validation.php');

function registerHandler($params)
{
    global $conn;

    if (!isValidUsername($params['username'])) {
        die(errorMsg("Username hat falsches Format"));
    }

    if (!isValidEmail($params['email'])) {
        die(errorMsg("E-Mail Adresse hat falsches Format"));
    }

    if (!isValidPassword($params['password'], 6, 30)) {
        die(errorMsg("Das Passwort muss zwischen 6 und 30 Zeichen lang sein"));
    }

    if (isUsernameUsed($params['username'])) {
        die(errorMsg("Username bereits vergeben"));
    }

    if (isEmailUsed($params['email'])) {
        die(errorMsg("E-Mail Adresse wird bereits verwendet"));
    }

    try {
        $password = password_hash($params['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO player (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindParam(":username", $params['username'], PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->bindParam(":email", $params['email'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['playerId'] = $conn->lastInsertId();
            $_SESSION['isAdmin'] = false;
            exit(successMsg("Erfolgreich eingeloggt"));
        } else {
            throw new PDOException("Fehler bei Registrierung");
        }
    } catch (PDOException $e) {
        die(errorMsg($e->getMessage()));
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
        $stmt->bindParam(":email", $username, PDO::PARAM_STR);
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
