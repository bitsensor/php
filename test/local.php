<?php
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;

require_once '../vendor/autoload.php';

global $debug;

$config = json_encode(array(
    Config::URI => 'http://localhost/test/api/',
    Config::USER => 'example_user',
    Config::API_KEY => 'abcdefghijklmnopqrstuvwxyz',
    Config::MODE => Config::MODE_ON,
    Config::CONNECTION_FAIL => Config::ACTION_BLOCK
));

//$config = file_get_contents('config.json');

$bitSensor = new BitSensor(new Config($config));

trigger_error('Test Error');

function test() {
    throw new Exception('Test Exception');
}

echo 'Allowed';

test();

echo 'Exception thrown';