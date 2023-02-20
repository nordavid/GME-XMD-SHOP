async function getPlayerData(playerId) {
    const response = await fetch(`./php/api.php/account/player?id=${playerId}`);
    const data = await response.json();
    if (!data.error) return data.payload;
}

async function getItemsForEntity(entityId) {
    const response = await fetch(`./php/api.php/entity/items?id=${entityId}`);
    const data = await response.json();
    if (!data.error) return data.payload;
    else return [];
}

async function getItemProperties(itemId) {
    const response = await fetch(`./php/api.php/shop/item/properties?id=${itemId}`);
    const data = await response.json();
    if (!data.error) return data.payload;
    else return [];
}
