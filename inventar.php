<?php
require_once("./php/check_login_status.php");
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Spielname</title>
    <link rel="stylesheet" href="css/style.css" />
    <style>
        @media only screen and (max-width: 1200px) and (orientation: portrait) {
            main {
                flex-direction: column-reverse;
            }
        }

        nav a:nth-of-type(3) {
            color: var(--farbe_akzent_primary);
        }

        nav a:nth-of-type(3):visited {
            color: var(--farbe_akzent_primary);
        }
    </style>
    <script src="js/script.js"></script>
    <script>
        window.addEventListener("load", initialisieren);

        function initialisieren() {
            // füge Funktion hinzu, um Itemkarten aufzuklappen
            let itemcards = document.querySelectorAll(".itemkarte");
            for (let itemkarte of itemcards) {
                itemkarte.addEventListener("click", () => {
                    setInventorycardEigenschaften(itemkarte, breakpoint);
                });

                // füge Funktion hinzu, um Itemkarten zuzuklappen
                // verhindere Bubbling -> andernfalls wird die Karte gleich wieder aufgeklappt
                let resetButton = itemkarte.querySelector(".resetKarte");
                resetButton.addEventListener("click", (e) => {
                    e.stopPropagation();
                    resetItemcardEigenschaften(itemkarte);
                });
            }

            addBurgerMenu();
        }
    </script>
</head>

<body>
    <header>
        <nav>
            <a href="index.php"><img src="img/logo_dummy.svg" alt="Logo" /></a>
            <button class="burgerMenu" type="button">
                <img src="img/burger.svg" alt="Burgermenue" />
            </button>
            <a class="burgerMenuLink" href="spieldummy.php">Spiel</a>
            <a class="burgerMenuLink" href="inventar.php">Inventar</a>
            <a class="burgerMenuLink" href="shop.php">Shop</a>
            <a class="burgerMenuLink" href="account.php">Account</a>
        </nav>
    </header>

    <main id="mainInventar">
        <!-- bitte Adresse und Name von jeweiligem item einfügen 
            später soll hier evtl. srcset rein für die responsiven Grafiken, dafür müssen wir aber
            erst die endgültigen Formate/Größen haben -->
        <article id="item-inventory" class="inventar">
            <section class="itemkarte_inventar itemkarte">
                <img class="itemBild" src="img/fishspaceship.jpg" alt="Name_item" />
                <p class="itemName">Der allerechteste ET-Funkel-Fingerhut</p>
                <p class="itemPreis">3000 Erkies</p>
                <p class="itemBeschreibung">
                    Mit dem ET-Funkel-Fingerhut kannst du nicht nur nach Hause telefonieren,
                    sondern hast Kontakt zur gesamten Galaxis. Der Spaß für extrem reife und
                    intergalaktische Telefonstreiche ist garantiert.
                </p>
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
                <form action="" method="">
                    <button class="resetKarte" type="button">zurück</button>
                </form>
            </section>
        </article>
        <article class="user">
            <!-- bitte Nickname in die figcaption setzen und Erkies
                auch hier soll später vllt noch srcset in das img -->
            <figure>
                <img src="img/avatar.jpg" alt="Avatar" />
                <figcaption id="player-username">Username</figcaption>
            </figure>
            <p id="player-balance">xxx Erkies</p>
        </article>
    </main>

    <script>
        fetch(`./php/api.php/account/player?id=<?php echo $_SESSION['playerId'] ?>`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (!data.error) {
                    document.getElementById("player-username").innerText = data.payload.username;
                    document.getElementById("player-balance").innerText = data.payload.balance + " Erkis";
                }
            })
            .catch(error => console.error("Error: ", error));

        fetch(`./php/api.php/entity/items?id=<?php echo $_SESSION['playerEntId'] ?>`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (!data.error) {
                    const itemInv = document.getElementById("item-inventory");
                    data.payload.forEach(item => {
                        addItemToInvContainer(itemInv, item);
                    });
                }
            })
            .catch(error => console.error("Error: ", error));

        function addItemToInvContainer(container, item) {
            const itemEl = `
            <section class="itemkarte_inventar itemkarte">
                <img class="itemBild" src="img/fishspaceship.jpg" alt="Name_item" />
                <p class="itemName">${item.name}</p>
                <p class="itemPreis">${item.cost}</p>
                <p class="itemBeschreibung">
                    Mit dem ET-Funkel-Fingerhut kannst du nicht nur nach Hause telefonieren,
                    sondern hast Kontakt zur gesamten Galaxis. Der Spaß für extrem reife und
                    intergalaktische Telefonstreiche ist garantiert.
                </p>
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
                <form action="" method="">
                    <button class="resetKarte" type="button">zurück</button>
                </form>
            </section>
            `;
            container.insertAdjacentHTML('beforeEnd', itemEl);
        }
    </script>
</body>

</html>