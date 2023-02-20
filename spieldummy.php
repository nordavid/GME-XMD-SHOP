<?php
require_once("./php/check_login_status.php");
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Spiel</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        nav a:nth-of-type(2) {
            color: var(--farbe_akzent_primary);
        }

        nav a:nth-of-type(2):visited {
            color: var(--farbe_akzent_primary);
        }
    </style>
    <script src="js/script.js"></script>
    <script>
        window.addEventListener('load', initialisieren);

        function initialisieren() {
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

    <main id="mainSpieldummy">
        <h1>Hier könnte Ihre Werbung stehen!</h1>
    </main>
</body>

</html>