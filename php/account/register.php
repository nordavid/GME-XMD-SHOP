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

    try {
        $stmt = $conn->prepare("SELECT id FROM player WHERE username = :username");
        $stmt->bindParam(":username", $params["username"], PDO::PARAM_STR);
        if ($stmt->rowCount() > 0) {
            throw new PDOException("Username existiert bereits");
        } else {
            $stmt = $conn->prepare("INSERT INTO player (username, password, email) VALUES (:username, :password, :email)");
            $password = password_hash($params['password'], PASSWORD_DEFAULT);
            $stmt->bindParam(":username", $params['username'], PDO::PARAM_STR);
            $stmt->bindParam(":password", $password, PDO::PARAM_STR);
            $stmt->bindParam(":email", $params['email'], PDO::PARAM_STR);
            if ($stmt->execute()) {
                // success
            } else {
                throw new PDOException("Fehler bei Registrierung");
            }
        }
    } catch (PDOException $e) {
        die(errorMsg($e->getMessage()));
    }
}
