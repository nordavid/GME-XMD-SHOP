<?php
session_start();
require_once('./constants.php');
require_once('./util/json_output.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_game_shop";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo errorMsg("Fehler bei Datenbankverbindung: " . $e->getMessage());
}
