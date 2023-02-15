<?php
// Verbindung zu den benötigten Datenbanken
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'db_login';
// Verbindung zu den Datenbanken
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    //Falls es einen Verbindungsfehler geben sollte wird das Script gestoppt und eine Fehlermeldung wiedergegeben
	exit('Fehler bei der Verbindung mit MySQL: ' . mysqli_connect_error()); //Ausgabe
}
//Prüfung, ob Daten in die Eingabefelder eingegeben wurden
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	// Wenn die Daten nicht erfasst werden konnten, Ausgabe:
	exit('Daten konnten nicht erfasst werden!');
}
// Falls die erforderlichen Eingabefelder nicht alle ausgefüllt wurden
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	// Ausgabe:
	exit('Bitte füllen Sie alle Felder aus!');
}                                                                                                                                                       
// Prüfung der E-Mail und des Usernamen
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    //Falls E-mail schon existiert: Ausgabe:
	exit('Diese E-Mail ist bereits vergeben!');
}
if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    //Falls Username nicht gültig ist: Ausgabe:
    exit('Dieser Username ist nicht gültig!');
}
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {//Prüfung, ob Anforderungen an Passwort erfüllt sind
    //Wenn nein: Ausgabe:
	exit('Das Passwort muss 5-20 Ziffern lang sein!'); 
}

if ($stmt = $con->prepare('SELECT ID, Passwort FROM account WHERE Username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
    //Speicheurng der Daten, um zu prüfen, ob der Account bereits in der Datenbank existiert
	if ($stmt->num_rows > 0) {
		// wenn der Username bereits existiert: Ausgabe:
		echo 'Dieser Username existiert bereits, bitte wählen Sie einen anderen!';
	} 
    else {
        if ($stmt = $con->prepare('SELECT ID, Passwort FROM account WHERE EMail = ?')) {
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $stmt->store_result();
            //Speicheurng der Daten, um zu prüfen, ob der Account bereits in der Datenbank existiert
            if ($stmt->num_rows > 0) {
                // wenn die E-Mail bereits existiert: Ausgabe:
                echo 'Diese E-Mail existiert bereits, bitte wählen Sie eine andere!';
            } else {
                // Wenn Username und E-Mail noch nicht existieren wird neuer Account erstellt:
                if ($stmt = $con->prepare('INSERT INTO account (Username, Passwort, EMail) VALUES (?, ?, ?)')) {	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
                    $stmt->execute();
                    echo 'Sie haben Sich erfolreich registriert und können nun zum Login!';
                }
                else {
                    // wenn etwas mit dem SQL-Statement falsch ist
                    echo 'Statement konnte nicht erstellt werden!';
                }
            }
        }
        else {
            // wenn etwas mit dem SQL-Statement falsch ist
            echo 'Statement konnte nicht erstellt werden!';
        }
    }
}
else {
	// wenn etwas mit dem SQL-Statement falsch ist
	echo 'Statement konnte nicht erstellt werden!';
}

$stmt->close();
$con->close();
?>