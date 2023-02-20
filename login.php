<?php
session_start();
if (isset($_GET['redirect'], $_SESSION['isLoggedIn'])) {
    $redirect = $_GET['redirect'];
    if ($redirect == "shop") {
        header('Location: shop.php');
        exit;
    } elseif ($redirect == "spieldummy") {
        header('Location: spieldummy.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css" />
    <style></style>
    <script src="./js/script.js"></script>
    <script>
        window.addEventListener('load', initialisieren);

        function initialisieren() {
            let loginContainer = document.querySelector('.login');
            let registrierenContainer = document.querySelector('.registrieren');
            let toggleLoginButton = document.querySelector('.toggleLogin');
            let toggleRegistrierenButton = document.querySelector('.toggleRegistrieren');

            // zeige gleichen Status des Feldes an bei Reload
            if (window.localStorage.getItem('state') === 'loginToggled') {
                loginContainer.style.marginLeft = '0';
                registrierenContainer.style.marginRight = '30rem';
                toggleLoginButton.style.backgroundColor = 'var(--farbe_mittel)';
                toggleRegistrierenButton.style.backgroundColor = 'var(--farbe_dunkel)';
            } else if (window.localStorage.getItem('state') === 'registrierenToggled') {
                registrierenContainer.style.marginLeft = '0';
                loginContainer.style.marginRight = '30rem';
                toggleRegistrierenButton.style.backgroundColor = 'var(--farbe_mittel)';
                toggleLoginButton.style.backgroundColor = 'var(--farbe_dunkel)';
            }

            // füge Mechanismus zum Togglen Login/Registrieren über die Buttons hinzu
            toggleLoginButton.addEventListener('click', () => {
                toggleLogin('login', loginContainer, registrierenContainer, toggleLoginButton, toggleRegistrierenButton, breakpoint);
                window.localStorage.setItem('state', 'loginToggled');
            });
            toggleRegistrierenButton.addEventListener('click', () => {
                toggleLogin('registrieren', registrierenContainer, loginContainer, toggleRegistrierenButton, toggleLoginButton, breakpoint);
                window.localStorage.setItem('state', 'registrierenToggled');
            });

            addBurgerMenu();
        }
    </script>
</head>

<body>
    <?php include_once("./components/navigation.php"); ?>

    <main id="mainLogin">
        <article class="loginContainer">
            <button class="toggleLogin" type="button">Login</button>
            <button class="toggleRegistrieren" type="button">Registrieren</button>

            <section class="login">
                <form id="login-form">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username">
                    <label for="passwort">Passwort</label>
                    <input type="password" id="passwort" name="password">
                    <button type="submit">Login</button>
                </form>
                <!-- Weiterleitung auf Shop oder Spiel -->
            </section>

            <section class="registrieren">
                <form id="register-form">
                    <label for="nickname">Username</label>
                    <input type="text" id="nickname" name="username">
                    <label for="emailAdresse">E-Mail Adresse</label>
                    <input type="text" id="emailAdresse" name="email">
                    <label for="passwort">Passwort</label>
                    <input type="password" id="passwort" name="password">
                    <input type="checkbox" id="start-items" name="startitems" checked />
                    <label for="start-items">Start Items</label>
                    <button type="submit">Registrieren</button>
                </form>
                <!-- Weiterleitung auf Shop oder Spiel -->
            </section>
        </article>
    </main>

    <script>
        const loginform = document.getElementById("login-form");
        loginform.addEventListener("submit", (event) => {
            event.preventDefault();
            const formData = new FormData(loginform);

            const request = new Request("./php/api.php/account/login", {
                method: "POST",
                body: formData,
            });

            fetch(request)
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    if (!data.error) {
                        redirect();
                    } else {
                        alert("Fehler: " + data.message);
                    }
                })
                .catch((error) => console.error("Error: ", error));
        });

        const registerform = document.getElementById("register-form");
        registerform.addEventListener("submit", (event) => {
            event.preventDefault();
            const formData = new FormData(registerform);
            const giveStartItems = document.getElementById("start-items").checked;
            formData.set("startitems", giveStartItems);

            const request = new Request("./php/api.php/account/register", {
                method: "POST",
                body: formData,
            });

            fetch(request)
                .then((response) => response.text())
                .then((data) => {
                    console.log(data);
                    if (!data.error) {
                        redirect();
                    } else {
                        alert("Fehler: " + data.message);
                    }
                })
                .catch((error) => console.error("Error: ", error));
        });

        function redirect() {
            const queryParams = new URLSearchParams(window.location.search);
            const redirect = queryParams.get("redirect");
            if (redirect) {
                window.location.href = "./" + redirect + ".php";
            } else {
                window.location.href = "./spieldummy.php";
            }
        }
    </script>
</body>

</html>