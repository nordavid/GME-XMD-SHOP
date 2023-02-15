<?php
session_start();

$servername = "localhost";
$username = "admin";
$password = "admin";
$dbname = "db_game_shop";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (Exception $e) {
    die(errorMsg("Error connecting to database: " . $e->getMessage()));
}
