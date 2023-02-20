<?php
require_once("./php/check_login_status.php");
require_once("./php/constants.php");
$_SESSION['shopEntId'] = ARMOR_SHOP;
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

        #merchant_2 {
            transform: scale(2);
        }

        #merchant_1,
        #merchant_3 {
            opacity: 50%;
            transform: scale(2);
            filter: saturate(0);
        }

        #merchant_1:hover,
        #merchant_3:hover {
            transform: scale(2.2);
        }
    </style>
    <script src="./js/script.js"></script>
    <script src="./js/apiRequests.js"></script>
    <script src="./js/shop.js"></script>
    <script>
        window.addEventListener('load', loadShop);

        function loadShop() {
            loadShopItems(<?php echo $_SESSION['playerEntId']; ?>, "sell-container", "Armor", "sell")
            loadShopItems(<?php echo $_SESSION['shopEntId']; ?>, "buy-container", "Armor", "buy")
            loadBalance(<?php echo $_SESSION['playerId']; ?>);
        }

        function initialisieren() {
            // verlinke auf die anderen Händler
            const merchant1 = document.querySelector('#merchant_1');
            const merchant1Content = merchant1.contentDocument;
            const merchant1Shape = merchant1Content.querySelector('.cls-3');
            const merchant3 = document.querySelector('#merchant_3');
            const merchant3Content = merchant3.contentDocument;
            const merchant3Shape = merchant3Content.querySelector('.cls-3');

            merchant1Shape.addEventListener('click', () => {
                window.location = 'shop_waffen.php';
            });
            merchant3Shape.addEventListener('click', () => {
                window.location = 'shop_schiffe.php';
            });

            let isOpen = false;
            // füge Funktion hinzu, um Itemkarten aufzuklappen
            // verhindere, dass gleichzeitig Kaufen und Verkaufen Karten umgedreht sind
            let itemcards = document.querySelectorAll('.itemkarte');
            for (let itemkarte of itemcards) {
                itemkarte.addEventListener('click', () => {
                    if (isOpen === false) {
                        isOpen = setItemcardEigenschaften(itemkarte)
                    }
                })

                // füge Funktion hinzu, um Itemkarten zuzuklappen
                // verhindere Bubbling -> andernfalls wird die Karte gleich wieder aufgeklappt                
                let resetButton = itemkarte.querySelector('.resetKarte');
                resetButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    isOpen = resetItemcardEigenschaften(itemkarte);
                });
            }

            const shopToggleButtonKaufen = document.querySelector('.shopToggleButton');
            const shopToggleButtonVerkaufen = shopToggleButtonKaufen.nextElementSibling;
            const kaufenContainer = document.querySelector('.kaufen');
            const verkaufenContainer = document.querySelector('.verkaufen');

            // zeige gleichen Status des Kaufen/Verkaufen-Feldes an bei Reload
            if (window.localStorage.getItem('state') === 'kaufenToggled') {
                toggleKaufen(kaufenContainer, verkaufenContainer, shopToggleButtonKaufen, shopToggleButtonVerkaufen, breakpoint);
            } else if (window.localStorage.getItem('state') === 'verkaufenToggled') {
                toggleKaufen(verkaufenContainer, kaufenContainer, shopToggleButtonVerkaufen, shopToggleButtonKaufen, breakpoint);
            }

            // zeige (in Mobilversion) jeweils den Kaufen oder Verkaufen-Reiter an
            shopToggleButtonKaufen.addEventListener('click', () => {
                toggleKaufen(kaufenContainer, verkaufenContainer, shopToggleButtonKaufen, shopToggleButtonVerkaufen, breakpoint);
                window.localStorage.setItem('state', 'kaufenToggled')
            });
            shopToggleButtonVerkaufen.addEventListener('click', () => {
                toggleKaufen(verkaufenContainer, kaufenContainer, shopToggleButtonVerkaufen, shopToggleButtonKaufen, breakpoint);
                window.localStorage.setItem('state', 'verkaufenToggled')
            })

            addBurgerMenu();
        }
    </script>
</head>

<body>
    <?php include_once("./components/navigation.php"); ?>

    <main class="mainShopEinzel">
        <!-- Hier bitte die Anzahl Erkies einfügen -->
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
            <p class="spruch"><span>Du hast <span id="balance">0</span> Erkies. Was willst du?</span></p>
        </section>

        <button class="shopToggleButton" type="button">kaufen</button>
        <button class="shopToggleButton" type="button">verkaufen</button>

        <section id="buy-container" class="kaufen">
        </section>

        <section id="sell-container" class="verkaufen">
        </section>
    </main>
</body>

</html>