<?php
require_once('./shop/shop_functions.php');

function itemSellHandler($itemId, $amount)
{
    if (!isset($_SESSION['shopEntId'], $_SESSION['playerEntId'], $_SESSION['playerId'])) {
        die(errorMsg("Es ist ein Fehler aufgetreten. Bitte logge Dich neu ein"));
    }

    $playerId = $_SESSION['playerId'];
    $shopEntId = $_SESSION['shopEntId'];
    $playerEntId = $_SESSION['playerEntId'];
    $itemCost = getItemCost($itemId);

    try {
        transferItems($playerEntId, $shopEntId, $itemId, $amount, TransferType::ToShop);
    } catch (Exception $e) {
        die(errorMsg($e->getMessage()));
    }
    updateBalance($playerId, + ($itemCost * $amount));
    echo successMsg("Item(s) erfolgreich verkauft");
}
