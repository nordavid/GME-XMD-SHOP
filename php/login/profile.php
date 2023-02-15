<?php

session_start();
// If-Schleife: 
if (!isset($_SESSION['eingeloggt'])) { //wenn der User nicht eingeloggt ist
	header('Location: index.html'); //zurück zur Index-Seite
	exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'db_login'; //Deklaration der Datenbanken
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME); //Verbindung mit den Datenbanken auf mySQL
if (mysqli_connect_errno()) { //wenn Verbindung nicht stattfindet
	exit('Fehler bei der Verbindung mit MySQL: ' . mysqli_connect_error());// Ausgabe
}

$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?'); //Benutze ID um auf die Ergebnisse der Datenbank zuzugreifen
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
?>