function loadItems(entityId, containerId, shopCategory, method) {
    fetch(`./php/api.php/entity/items?id=${entityId}`)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (!data.error) {
                const itemInv = document.getElementById(containerId);
                itemInv.innerHTML = "";
                data.payload.forEach((item) => {
                    addItemToInvContainer(itemInv, item, shopCategory, method);
                });
                if (method == "buy") {
                    addBuyFormListeners();
                } else {
                    addSellFormListeneres();
                }
                initialisieren();
            }
        })
        .then(() => {})
        .catch((error) => console.error("Error: ", error));
}

function addItemToInvContainer(container, item, shopCategory, method) {
    let isDeactivated = false;
    if (shopCategory != item.category || item.amount == 0) isDeactivated = true;

    const itemEl = `
    <section class="itemkarte ${isDeactivated && "itemkarte_nichtverfuegbar"}">
    <div class="itemAmount">${item.amount}</div>
    <img class="itemBild" src="./uploads/${item.image}" alt="Name_item">
    <p class="itemName">${item.name}</p>
    <p class="itemPreis">${item.cost}</p>
    <p class="itemBeschreibung">${item.description}</p>
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
    <form class="${method == "sell" ? "sell-form" : "buy-form"}">
        <!-- hier schon min/max in Abhängigkeit vom Stock des Händlers? -->
        <input type="hidden" name="id" value="${item.id}">
        <button class="resetKarte" type="button">abbrechen</button>
        <input type="number" min="1" max="${
            item.amount
        }" value="1" name="amount"  id="anzahl_kaufen">
        <button type="submit">${method == "sell" ? "verkaufen" : "kaufen"}</button>
    </form>
</section>
            `;

    container.insertAdjacentHTML("beforeEnd", itemEl);
}

function initialisieren() {
    // verlinke auf die anderen Händler
    const merchant2 = document.querySelector("#merchant_2");
    const merchant2Content = merchant2.contentDocument;
    const merchant2Shape = merchant2Content.querySelector(".cls-3");

    const merchant3 = document.querySelector("#merchant_3");
    const merchant3Content = merchant3.contentDocument;
    const merchant3Shape = merchant3Content.querySelector(".cls-3");

    merchant2Shape.addEventListener("click", () => {
        window.location = "shop_ruestung.php";
    });
    merchant3Shape.addEventListener("click", () => {
        window.location = "shop_schiffe.php";
    });

    let isOpen = false;

    // füge Funktion hinzu, um Itemkarten aufzuklappen
    // verhindere, dass gleichzeitig Kaufen und Verkaufen Karten umgedreht sind
    let itemcards = document.querySelectorAll(".itemkarte");
    for (let itemkarte of itemcards) {
        itemkarte.addEventListener("click", () => {
            if (isOpen === false) {
                isOpen = setItemcardEigenschaften(itemkarte);
            }
        });

        // füge Funktion hinzu, um Itemkarten zuzuklappen
        // verhindere Bubbling -> andernfalls wird die Karte gleich wieder aufgeklappt
        let resetButton = itemkarte.querySelector(".resetKarte");
        resetButton.addEventListener("click", (e) => {
            e.stopPropagation();
            isOpen = resetItemcardEigenschaften(itemkarte);
        });
    }

    const shopToggleButtonKaufen = document.querySelector(".shopToggleButton");
    const shopToggleButtonVerkaufen = shopToggleButtonKaufen.nextElementSibling;
    const kaufenContainer = document.querySelector(".kaufen");
    const verkaufenContainer = document.querySelector(".verkaufen");
    // zeige gleichen Status des Feldes an bei Reload
    if (window.localStorage.getItem("state") === "kaufenToggled") {
        toggleKaufen(
            kaufenContainer,
            verkaufenContainer,
            shopToggleButtonKaufen,
            shopToggleButtonVerkaufen,
            breakpoint
        );
    } else if (window.localStorage.getItem("state") === "verkaufenToggled") {
        toggleKaufen(
            verkaufenContainer,
            kaufenContainer,
            shopToggleButtonVerkaufen,
            shopToggleButtonKaufen,
            breakpoint
        );
    }

    // zeige (in Mobilversion) jeweils den Kaufen oder Verkaufen-Reiter an
    shopToggleButtonKaufen.addEventListener("click", () => {
        toggleKaufen(
            kaufenContainer,
            verkaufenContainer,
            shopToggleButtonKaufen,
            shopToggleButtonVerkaufen,
            breakpoint
        );
        window.localStorage.setItem("state", "kaufenToggled");
    });
    shopToggleButtonVerkaufen.addEventListener("click", () => {
        toggleKaufen(
            verkaufenContainer,
            kaufenContainer,
            shopToggleButtonVerkaufen,
            shopToggleButtonKaufen,
            breakpoint
        );
        window.localStorage.setItem("state", "verkaufenToggled");
    });

    addBurgerMenu();
}
