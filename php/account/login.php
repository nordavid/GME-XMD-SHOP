<?php
session_start();

function loginHandler($params)
{
    global $conn;

    if (!checkRequiredParams($params, ["username", "password"])) {
        die(errorMsg("Bitte tragen sie einen Usernamen und ein Passwort ein"));
    }

    try {
        $stmt = $conn->prepare('SELECT  id, username, password, is_admin FROM player WHERE username = :username');
        $stmt->bindParam(1, $params['username'], PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        die(errorMsg());
    }

    if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($params['password'], $result['password'])) {
            if (password_needs_rehash($result['password'], PASSWORD_DEFAULT)) {

                try {
                    $newPasswordHash = password_hash($params['password'], PASSWORD_DEFAULT);

                    $query = $conn->prepare('UPDATE player SET password = :newHash WHERE username = :username;');
                    $query->bindParam(":newHash", $newPasswordHash, PDO::PARAM_STR);
                    $query->bindParam(":username", $params['username'], PDO::PARAM_STR);

                    $query->execute();
                } catch (PDOException $e) {
                    die(errorMsg());
                }
            }
            // logged in
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['playerId'] = $result['id'];
            $_SESSION['isAdmin'] = $result['is_admin'];
            exit(successMsg("Erfolgreich eingeloggt"));
        }
    }
}

function checkRequiredParams($params, $required)
{
    foreach ($required as $paramKey) {
        if (empty($params[$paramKey])) {
            return false;
        }
    }
    return true;
}
