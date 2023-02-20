async function loadInventoryItems(entityId) {
    const itemInv = document.getElementById("item-inventory");
    const items = await getItemsForEntity(entityId);

    for (const item of items) {
        const itemProps = await getItemProperties(item.id);
        addItemToInvContainer(itemInv, item, itemProps);
    }

    initialisieren();
}

function addItemToInvContainer(container, item, itemProps) {
    const itemEl = `
            <section class="itemkarte_inventar itemkarte">
            <div class="itemAmount">${item.amount}</div>
                <img class="itemBild" src="./uploads/${item.image}" alt="Name_item" />
                <p class="itemName">${item.name}</p>
                <p class="itemPreis">${item.cost} ERKIS</p>
                <p class="itemBeschreibung">
                    ${item.description}
                </p>
                <table>
                    <tr>
                        <th>Seltenheit</th>
                        <td>${item.rarity}</td>
                    </tr>
                    ${itemProps
                        .map(
                            (itemProp) => `
                    <tr>
                        <th>${itemProp.name}</th>
                        <td>${itemProp.value}</td>
                    </tr>`
                        )
                        .join("")}
                </table>
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
