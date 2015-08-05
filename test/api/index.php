<?php
header('Content-Type: application/json');

$request = json_decode(file_get_contents('php://input'), true);

$response = array();

switch ($request['action']) {
    case 'authorize':
        $response['response'] = 'allow';
        break;
    case 'log':
        $response['response'] = 'ok';
        break;
}

echo json_encode($response);