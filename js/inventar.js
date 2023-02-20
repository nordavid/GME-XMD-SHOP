function loadPlayerData(playerId) {
    fetch(`./php/api.php/account/player?id=${playerId}`)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (!data.error) {
                document.getElementById("player-username").innerText = data.payload.username;
                document.getElementById("player-balance").innerText =
                    data.payload.balance + " Erkis";
            }
        })
        .catch((error) => console.error("Error: ", error));
}

function loadItems(entityId) {
    fetch(`./php/api.php/entity/items?id=${entityId}`)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (!data.error) {
                const itemInv = document.getElementById("item-inventory");
                data.payload.forEach((item) => {
                    addItemToInvContainer(itemInv, item);
                });
                initialisieren();
            }
        })
        .catch((error) => console.error("Error: ", error));
}

function addItemToInvContainer(container, item) {
    const itemEl = `
            <section class="itemkarte_inventar itemkarte">
                <img class="itemBild" src="img/fishspaceship.jpg" alt="Name_item" />
                <p class="itemName">${item.name}</p>
                <p class="itemPreis">${item.cost}</p>
                <p class="itemBeschreibung">
                    ${item.description}
                </p>
                <table>
                    <tr>
                        <th>Seltenheit</th>
                        <td>${item.rarity}</td>
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
    container.insertAdjacentHTML("beforeEnd", itemEl);
}

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
