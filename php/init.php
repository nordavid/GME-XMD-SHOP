<?php
ini_set('session.cookie_samesite', 'Strict');
session_start();
require_once('./util/json_output.php');

$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "db_game_shop";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo errorMsg("Fehler bei Datenbankverbindung: " . $e->getMessage());
}
