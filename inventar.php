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
    <script src="./js/script.js"></script>
    <script src="./js/apiRequests.js"></script>
    <script src="./js/inventar.js"></script>
    <script>
        window.onload = () => {
            getPlayerData(<?php echo $_SESSION['playerId']; ?>).then(playerData => {
                document.getElementById("player-username").innerText = playerData.username;
                document.getElementById("player-balance").innerText = playerData.balance + " ERKIES";
            })
            loadInventoryItems(<?php echo $_SESSION['playerEntId']; ?>)
        }
    </script>
</head>

<body>
    <?php include_once("./components/navigation.php"); ?>

    <main id="mainInventar">
        <!-- bitte Adresse und Name von jeweiligem item einfügen 
            später soll hier evtl. srcset rein für die responsiven Grafiken, dafür müssen wir aber
            erst die endgültigen Formate/Größen haben -->
        <article id="item-inventory" class="inventar">
        </article>
        <article class="user">
            <!-- bitte Nickname in die figcaption setzen und Erkies
                auch hier soll später vllt noch srcset in das img -->
            <figure>
                <img src="./img/avatar_default.png" alt="Avatar" />
                <figcaption id="player-username">Username</figcaption>
            </figure>
            <p id="player-balance">xxx Erkies</p>
        </article>
    </main>
</body>

</html>