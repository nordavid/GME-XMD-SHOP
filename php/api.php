<?php
require_once('./init.php');
header('Content-Type: application/json');

$requestUri = $_SERVER['REQUEST_URI'];
$pathComponents = explode('/', $requestUri);
$endpoint = array_slice($pathComponents, array_search(basename(__FILE__), $pathComponents) + 1)[0];
$endpoint = strtok($endpoint, '?');

$data = json_decode(file_get_contents('php://input'), true);
echo  json_encode($data);
exit;

// API routes
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $parameters = $_GET;
    if ($endpoint == 'users') {
        $id = $parameters['id'];
        $user = getUser($id);
        echo json_encode($user);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $parameters = $_POST;
    if ($endpoint == 'users') {
        $id = $parameters['id'];
        $user = getUser($id);
        echo json_encode($user);
    }
}

function getUser($id)
{
    // Your code to get user information
    $user = array(
        'id' => $id,
        'name' => 'John Doe',
        'email' => 'john.doe@example.com'
    );
    return $user;
}
