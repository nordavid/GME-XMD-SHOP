<?php
require_once('./shop/shop_functions.php');

function itemSellHandler($itemId, $amount)
{
    if (!isset($_SESSION['shopEntId'], $_SESSION['playerEntId'], $_SESSION['playerId'])) {
        die(errorMsg("Es ist ein Fehler aufgetreten. Bitte logge Dich neu ein"));
    }

    $shopEntId = $_SESSION['shopEntId'];
    $playerEntId = $_SESSION['playerEntId'];

    try {
        transferItems($playerEntId, $shopEntId, $itemId, $amount);
    } catch (Exception $e) {
        die(errorMsg($e->getMessage()));
    }
}
