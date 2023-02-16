<?php
require_once('./init.php');
header('Content-Type: application/json');

$requestUri = $_SERVER['REQUEST_URI'];
$pathComponents = explode('/', $requestUri);
$endpoint = array_slice($pathComponents, array_search(basename(__FILE__), $pathComponents) + 1)[0];
$endpoint = strtok($endpoint, '?');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        handlePostRequest($endpoint, $_GET);
        break;
    case 'POST':
        handlePostRequest($endpoint, $_POST);
        break;
    default:
        die(errorMsg("Ungültige Request-Methode"));
        break;
}

// API routes
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $parameters = $_GET;
    if ($endpoint == 'players') {
        $id = $parameters['id'];
        $player = getPlayer($id);
        echo json_encode($player);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //$json = file_get_contents('php://input');
    //$parameters = json_decode($json, true);
    $parameters = $_POST;
    if ($endpoint == 'players') {
        $id = $parameters['id'];
        $player = getPlayer($id);
        if ($player) {
            echo successMsg($player);
        } else {
            echo errorMsg("Spieler nicht gefunden!");
        }
    }
}

function handleGetRequest($endpoint, $params)
{
    switch ($endpoint) {
        case 'players':
            # code...
            break;

        default:
            # code...
            break;
    }
}

function handlePostRequest($endpoint, $params)
{
}

function getPlayer($id)
{
    global $conn;
    $sql = "SELECT * FROM player WHERE player.id = ?;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else return null;
}
