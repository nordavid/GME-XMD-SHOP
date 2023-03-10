<?php
require_once("./php/check_login_status.php");
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Shop</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        nav a:nth-of-type(4) {
            color: var(--farbe_akzent_primary);
        }

        nav a:nth-of-type(4):visited {
            color: var(--farbe_akzent_primary);
        }

        .merchants_bild {
            height: 100vh;
        }

        .figure_merchant {
            width: 33vw;
            max-width: 33%;
            height: 100vh;
        }

        .kaufen,
        .verkaufen {
            display: none;
        }

        .shopToggleButton {
            display: none;
        }

        @media only screen and (max-width: 1200px) and (orientation: portrait) {
            .figure_merchant {
                width: 100vw;
                max-width: 100%;
                height: auto;
            }

        }
    </style>
    <script src="./js/script.js"></script>
    <script>
        window.addEventListener('load', initialisieren);

        // sprich den jeweiligen transparenten Pfad der Händler in den SVG an
        // füge Event Listener für Link und Animation hinzu
        function initialisieren() {
            const merchant1 = document.querySelector('#merchant_1');
            const merchant1Content = merchant1.contentDocument;
            const merchant1Shape = merchant1Content.querySelector('.cls-3');

            const merchant2 = document.querySelector('#merchant_2');
            const merchant2Content = merchant2.contentDocument;
            const merchant2Shape = merchant2Content.querySelector('.cls-3')

            const merchant3 = document.querySelector('#merchant_3');
            const merchant3Content = merchant3.contentDocument;
            const merchant3Shape = merchant3Content.querySelector('.cls-3');

            const merchantSection = document.querySelector('.merchants_bild');
            const merchantFigures = document.querySelectorAll('figure');

            const inventorySections = document.querySelectorAll('.kaufen, .verkaufen');
            const toggleButtons = document.querySelectorAll('.shopToggleButton');

            merchant1Shape.addEventListener('click', () => {
                zoomIn(merchant1, breakpoint);
                fadeOut(merchant2, merchant3, breakpoint);
                animateRemainder(merchantSection, merchantFigures, inventorySections, toggleButtons, breakpoint);
                redirect('shop_waffen.php', breakpoint);
            });
            merchant2Shape.addEventListener('click', () => {
                zoomIn(merchant2, breakpoint);
                fadeOut(merchant1, merchant3, breakpoint);
                animateRemainder(merchantSection, merchantFigures, inventorySections, toggleButtons, breakpoint);
                redirect('shop_ruestung.php', breakpoint)
            });
            merchant3Shape.addEventListener('click', () => {
                zoomIn(merchant3, breakpoint);
                fadeOut(merchant1, merchant2, breakpoint);
                animateRemainder(merchantSection, merchantFigures, inventorySections, toggleButtons, breakpoint);
                redirect('shop_schiffe.php', breakpoint)
            });

            addBurgerMenu();
        }
    </script>
</head>

<body>
    <?php include_once("./components/navigation.php"); ?>

    <main id="mainShop">
        <section class="merchants_bild">
            <!-- Hintergrundbild vom Markt wird über CSS eingefügt -->
            <figure class="figure_merchant">
                <object type="image/svg+xml" data="img/merchant_1.svg" class="merchant_img" id="merchant_1"></object>
            </figure>
            <figure class="figure_merchant">
                <object type="image/svg+xml" data="img/merchant_2.svg" class="merchant_img" id="merchant_2"></object>
            </figure>
            <figure class="figure_merchant">
                <object type="image/svg+xml" data="img/merchant_3.svg" class="merchant_img" id="merchant_3"></object>
            </figure>
        </section>


        <!-- die beiden folgenden sollen leer bleiben bis auf den Button, dient der Animation/Formatierung-->
        <button class="shopToggleButton" type="button">kaufen</button>
        <button class="shopToggleButton" type="button">verkaufen</button>
        <section class="kaufen">
        </section>

        <section class="verkaufen">
        </section>
    </main>
</body>

</html>