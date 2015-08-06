<?php
header('Content-Type: application/json');

$response = array();

$response['response'] = 'ok';

echo json_encode($response);