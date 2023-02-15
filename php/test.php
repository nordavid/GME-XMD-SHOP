<?php

$requestUri = $_SERVER['REQUEST_URI'];

$pathComponents = explode('/', $requestUri);
$params = array_slice($pathComponents, array_search(basename(__FILE__), $pathComponents) + 1);
$params[] = $_POST['entityId'];

switch ($params[0]) {
    case 'items':
        # code...
        break;

    default:
        # code...
        break;
}

echo json_encode($params);
