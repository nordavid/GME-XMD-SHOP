<!DOCTYPE html>
<html lang="de">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Spielname</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
    </style>
    <script src="./js/script.js"></script>
    <script>
        window.addEventListener('load', initialisieren);

        function initialisieren() {
            addBurgerMenu();
        }
    </script>
</head>

<body id="bodyIndex">
    <?php include_once("./components/navigation.php"); ?>

    <main id="mainIndex">
        <!--<picture>
                <source media="(orientation: portrait)" srcset="img/bg_portrait.jpg">
                <img src="img/bg_landscape.jpg" alt="Landing Page Background">
            </picture> -->
        <h1>fette Überschrift für das Spiel</h1>
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
            Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
            Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>
        <a class="spielLink" href="login.php?redirect=spieldummy">Spiel</a>
        <a class="spielLink" href="login.php?redirect=shop">Shop</a>
    </main>
</body>

</html>