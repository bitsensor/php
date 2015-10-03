<?php
header('Content-Type: application/json');

function getApiKey($user) {
    switch ($user) {
        case 'example_user':
            return 'abcdefghijklmnopqrstuvwxyz';
        default:
            return '';
    }
}

$user = $_GET['user'];
$receivedSignature = $_GET['sig'];

$content = file_get_contents('php://input');

$signature = hash_hmac('sha256', $content, getApiKey($user));

$response = array();

if (strcmp($signature, $receivedSignature) === 0) {
    $response['response'] = 'allow';
} else {
    $response['response'] = 'access_denied';
}

echo json_encode($response);
