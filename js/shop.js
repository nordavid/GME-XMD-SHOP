async function loadBalance(playerId) {
    const playerData = await getPlayerData(playerId);
    document.getElementById("balance").innerText = playerData.balance;
}

async function loadShopItems(entityId, containerId, shopCategory, method) {
    console.log("Ent: " + entityId);
    const itemInv = document.getElementById(containerId);
    itemInv.innerHTML = "";
    const items = await getItemsForEntity(entityId);

    for (const item of items) {
        const itemProps = await getItemProperties(item.id);
        addItemToInvContainer(itemInv, item, itemProps, shopCategory, method);
    }

    if (method == "buy") {
        addBuyFormListeners();
    } else {
        addSellFormListeneres();
    }

    initialisieren();
}

function addItemToInvContainer(container, item, itemProps, shopCategory, method) {
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
            <td>${item.rarity}</td>
        </tr>
        ${
            itemProps.length >= 0 &&
            itemProps
                .map(
                    (itemProp) => `
        <tr>
            <th>${itemProp.name}</th>
            <td>${itemProp.value}</td>
        </tr>`
                )
                .join("")
        }
    </table>
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
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    if (!data.error) {
                        loadShop();
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
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    if (!data.error) {
                        loadShop();
                    }
                })
                .catch((error) => console.error(error));
        });
    });
}
