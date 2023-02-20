<?php
session_start();
if (!isset($_SESSION['isLoggedIn'])) {
    header('Location: login.php');
    exit;
}
