<?php
require_once("./php/check_login_status.php");
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Account</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        nav a:nth-of-type(5) {
            color: var(--farbe_akzent_primary);
        }

        nav a:nth-of-type(5):visited {
            color: var(--farbe_akzent_primary);
        }
    </style>
    <script src="js/script.js"></script>
    <script>
        window.addEventListener('load', initialisieren);

        function initialisieren() {
            // füge Funktion hinzu, um Passwort ändern-Feld aufzuklappen
            let formPasswort = document.querySelector('.passwortAendern');
            let buttonAufklappen = document.querySelector('.account > button');
            buttonAufklappen.addEventListener('click', aufklappen);

            document.getElementById("logout-button").addEventListener("click", (event) => {
                const request = new Request("./php/api.php/account/logout", {
                    method: "POST",
                });

                fetch(request)
                    .then((response) => response.json())
                    .then((data) => {
                        console.log(data);
                        if (!data.error) {
                            window.location.href = "./index.php";
                        } else {
                            alert("Fehler");
                        }
                    })
                    .catch((error) => console.error("Error: ", error));
            })

            addBurgerMenu();
        }
    </script>
</head>

<body>
    <header>
        <nav>
            <a href="index.php"><img src="img/logo_dummy.svg" alt="Logo"></a>
            <button class="burgerMenu" type="button"><img src="img/burger.svg" alt="Burgermenue"></button>
            <a class="burgerMenuLink" href="spieldummy.php">Spiel</a>
            <a class="burgerMenuLink" href="inventar.php">Inventar</a>
            <a class="burgerMenuLink" href="shop.php">Shop</a>
            <a class="burgerMenuLink" href="account.php">Account</a>
        </nav>
    </header>

    <main id="account">
        <!-- bitte Nickname in die figcaption setzen-->
        <article class="account">
            <figure class="accountFigure">
                <img src="img/avatar.jpg">
                <figcaption>Username</figcaption>
            </figure>
            <button type="button">Passwort ändern</button>
            <!-- das Formfeld soll versteckt werden bis der Button gedrückt wird -->
            <form class="passwortAendern">
                <label for="passwort_aendern">neues Passwort</label>
                <input type="password" name="passwort_aendern" id="passwort_aendern">
                <button type="submit">absenden</button>
                <!-- hier ggf. eine Erfolgsmeldung -->
            </form>
            <button id="logout-button" type="submit">Logout</button>
            <!-- hier ggf. eine Erfolgsmeldung -->
        </article>
    </main>
</body>

</html>