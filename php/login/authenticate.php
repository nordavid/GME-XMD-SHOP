<?php
session_start(); //Erstellung einer Session, in der die verschiedenen Datenbanken deklariert werden
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'db_login';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME); //Verknüpfung mit den Datenbanken auf MySQL
if ( mysqli_connect_errno() ) {//if-Schleife: falls es einen Fehler mit der Verbindung gibt soll das Script gestoppt werden und auf den Fehler verwiesen werden
	
	exit('Fehler bei der Verbindung mit MySQL: ' . mysqli_connect_error());
} 


//if-Schleife:
if ( !isset($_POST['username'], $_POST['password']) ) { //Prüfung, ob der Username unddas Passwort eingegeben wurden
	exit('Bitte tragen sie einen Usernamen und ein Passwort ein!');// Wenn kein Username und/ oder kein Passwort gefunden wurde, wird die Schleife verlassen und ausgegeben: Bitte tragen sie einen Usernamen und ein Passwort ein!
}

//Vorbereitung der Datenbanken
if ($stmt = $con->prepare('SELECT ID, Passwort FROM account WHERE Username = ?')) {
	//Festlegung der Parameter
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();//Speicherung der Ergebnisse, damit geprüft werden kann, ob der Account in der Datenbank vorhanden ist

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch(); // Wenn der Account existiert, wird das Passwort verifiziert
        
        if (password_verify($_POST['password'], $password)) { //Wenn die Verifikation erfolgreich war wird der User eingeloggt
            session_regenerate_id(); //Erstellung von Sessions, damit man weiß, dass der User eingeloggt ist
            $_SESSION['eingeloggt'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: home.php');
        } else {
            
            echo 'Falscher Username und/ oder falsches Passwort!'; //Ausgabe, wenn das Passwort falsch ist.
        }
    } else {
       
        echo 'Falscher Username und/ oder falsches Passwort!'; //Ausgabe, wenn der Username falsch ist.
    }

	$stmt->close();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Profile Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
	</head>
	<body class="eingeloggt">
		<nav class="navtop">
			<div>
				<h1>Spielname</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profil</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Profil-Seite</h2>
			<div>
				<p>Deine Account-Details sind unten:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['username']?></td>
					</tr>
					<tr>
						<td>Passwort:</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>E-Mail:</td>
						<td><?=$email?></td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>