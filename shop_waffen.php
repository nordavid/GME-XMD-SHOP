<?php
require_once("./php/check_login_status.php");
$_SESSION['shopEntId'] = 1;
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Shop Waffen</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        nav a:nth-of-type(4) {
            color: var(--farbe_akzent_primary);
        }

        nav a:nth-of-type(4):visited {
            color: var(--farbe_akzent_primary);
        }

        #merchant_1 {
            transform: scale(2);
        }

        #merchant_2,
        #merchant_3 {
            opacity: 50%;
            transform: scale(2);
            filter: saturate(0);
        }

        #merchant_2:hover,
        #merchant_3:hover {
            transform: scale(2.2);
        }
    </style>
    <script src="./js/script.js"></script>
    <script src="./js/shop.js"></script>
    <script>
        window.onload = () => {
            loadItems(<?php echo $_SESSION['playerEntId']; ?>, "sell-container", "Weapon", "sell")
            loadItems(<?php echo $_SESSION['shopEntId']; ?>, "buy-container", "Weapon", "buy")
        }

        function addBuyFormListeners() {
            const buyForms = document.querySelectorAll(".buy-form");
            buyForms.forEach((form) => {
                form.addEventListener("submit", (event) => {
                    event.preventDefault();
                    const formData = new FormData(form);

                    console.log(formData);

                    fetch(`./php/api.php/shop/item/buy`, {
                            method: "POST",
                            body: formData,
                        })
                        .then((response) => response.text())
                        .then((data) => {
                            console.log(data);
                            if (!data.error) {
                                loadItems(<?php echo $_SESSION['playerEntId']; ?>, "sell-container", "Weapon", "sell")
                                loadItems(<?php echo $_SESSION['shopEntId']; ?>, "buy-container", "Weapon", "buy")
                            }
                        })
                        .catch((error) => console.error(error));
                });
            });
        }

        function addSellFormListeneres() {
            const sellForms = document.querySelectorAll(".sell-form");
            sellForms.forEach((form) => {
                form.addEventListener("submit", (event) => {
                    event.preventDefault();
                    const formData = new FormData(form);

                    console.log(formData);

                    fetch(`./php/api.php/shop/item/sell`, {
                            method: "POST",
                            body: formData,
                        })
                        .then((response) => response.text())
                        .then((data) => {
                            console.log(data);
                            if (!data.error) {
                                loadItems(<?php echo $_SESSION['playerEntId']; ?>, "sell-container", "Weapon", "sell")
                                loadItems(<?php echo $_SESSION['shopEntId']; ?>, "buy-container", "Weapon", "buy")
                            }
                        })
                        .catch((error) => console.error(error));
                });
            });
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
            <p class="spruch">Du hast xx Erkies. Was willst du?</p>
        </section>

        <!-- das ist ein Button, weil er in der Mobilansicht zum togglen da ist -->
        <button class="shopToggleButton" type="button">kaufen</button>
        <button class="shopToggleButton" type="button">verkaufen</button>

        <section id="buy-container" class="kaufen">


            <!-- bitte Adresse und Name von jeweiligem item einfügen 
                später soll hier evtl. srcset rein für die responsiven Grafiken, dafür müssen wir aber
                erst die endgültigen Formate/Größen haben -->
            <section class="itemkarte">
                <img class="itemBild" src="img/item.png" alt="Name_item">
                <p class="itemName">Der allerechteste ET-Funkel-Fingerhut</p>
                <p class="itemPreis">3000 Erkies</p>
                <p class="itemBeschreibung">Mit dem ET-Funkel-Fingerhut kannst du nicht nur nach Hause telefonieren, sondern hast Kontakt zur gesamten Galaxis. Der Spaß für extrem reife und intergalaktische Telefonstreiche ist garantiert.</p>
                <table>
                    <tr>
                        <th>Seltenheit</th>
                        <td>legendär</td>
                    </tr>
                    <tr>
                        <th>Rüstung</th>
                        <td>5</td>
                    </tr>
                    <tr>
                        <th>Eigenschaften</th>
                        <td>+500 Charisma, +300 Intelligenz</td>
                    </tr>
                </table>
                <p class="buff">kurzer Statuseffekt</p>
                <form>
                    <!-- hier schon min/max in Abhängigkeit vom Stock des Händlers? -->
                    <button class="resetKarte" type="button">zurück</button>
                    <input type="number" name="anzahl_kaufen" id="anzahl_kaufen">
                    <button type="submit">kaufen</button>
                </form>
            </section>
        </section>

        <section id="sell-container" class="verkaufen">
            <!-- bitte Adresse und Name von jeweiligem item einfügen 
                später soll hier evtl. srcset rein für die responsiven Grafiken, dafür müssen wir aber
                erst die endgültigen Formate/Größen haben 
                wenn das Item nicht verkaufbar ist bei dem jeweiligen Händler, bitte class="itemkarte_nichtverfuegbar"
                damit wir die ausgrauen können -->
            <section class="itemkarte">
                <img class="itemBild" src="img/item.png" alt="Name_item">
                <p class="itemName">Der allerechteste ET-Funkel-Fingerhut</p>
                <p class="itemPreis">3000 Erkies</p>
                <p class="itemBeschreibung">Mit dem ET-Funkel-Fingerhut kannst du nicht nur nach Hause telefonieren, sondern hast Kontakt zur gesamten Galaxis. Der Spaß für extrem reife und intergalaktische Telefonstreiche ist garantiert.</p>
                <table>
                    <tr>
                        <th>Seltenheit</th>
                        <td>legendär</td>
                    </tr>
                    <tr>
                        <th>Rüstung</th>
                        <td>5</td>
                    </tr>
                    <tr>
                        <th>Eigenschaften</th>
                        <td>+500 Charisma, +300 Intelligenz</td>
                    </tr>
                </table>
                <p class="buff">kurzer Statuseffekt</p>
                <form>
                    <!-- hier schon min/max in Abhängigkeit vom Stock des Händlers? -->
                    <button class="resetKarte" type="button">abbrechen</button>
                    <input type="number" name="anzahl_kaufen" id="anzahl_kaufen">
                    <button type="submit">verkaufen</button>
                </form>
            </section>
        </section>
    </main>
</body>

</html>