<?php
require_once('./shop/shop_functions.php');

function itemBuyHandler($itemId, $amount)
{
    if (!isset($_SESSION['shopEntId'], $_SESSION['playerEntId'], $_SESSION['playerId'])) {
        die(errorMsg("Es ist ein Fehler aufgetreten. Bitte logge Dich neu ein"));
    }

    $playerId = $_SESSION['playerId'];
    $shopEntId = $_SESSION['shopEntId'];
    $playerEntId = $_SESSION['playerEntId'];
    $itemCost = getItemCost($itemId);
    $playerBalance = getBalance($playerId);

    if ($playerBalance >= $itemCost * $amount) {
        // Enough balance to buy
        try {
            transferItems($shopEntId, $playerEntId, $itemId, $amount, TransferType::ToPlayer);
        } catch (Exception $e) {
            die(errorMsg($e->getMessage()));
        }
        updateBalance($playerId, - ($itemCost * $amount));
        echo successMsg("Item(s) erfolgreich gekauft");
    } else {
        die(errorMsg("Nicht genug Erkies zum Kaufen"));
    }
}
