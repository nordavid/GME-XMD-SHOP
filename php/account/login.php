<?php
function loginHandler($username, $password)
{
    global $conn;

    try {
        $stmt = $conn->prepare('SELECT  id, entity_id, username, password, is_admin FROM player WHERE username = :username');
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            die(errorMsg("Username oder Passwort falsch"));
        }
    } catch (PDOException $e) {
        die(errorMsg());
    }

    if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (password_verify($password, $result['password'])) {
            if (password_needs_rehash($result['password'], PASSWORD_DEFAULT)) {

                try {
                    $newPasswordHash = password_hash($password, PASSWORD_DEFAULT);

                    $query = $conn->prepare('UPDATE player SET password = :newHash WHERE username = :username;');
                    $query->bindParam(":newHash", $newPasswordHash, PDO::PARAM_STR);
                    $query->bindParam(":username", $username, PDO::PARAM_STR);

                    $query->execute();
                } catch (PDOException $e) {
                    die(errorMsg());
                }
            }
            // logged in
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['playerEntId'] = $result['entity_id'];
            $_SESSION['playerId'] = $result['id'];
            $_SESSION['isAdmin'] = $result['is_admin'];
            exit(successMsg("Erfolgreich eingeloggt"));
        }
    }
}
