<?php
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;

require_once '../vendor/autoload.php';

global $debug;

$config = json_encode(array(
    Config::URI => 'http://192.168.159.144:8080/data',
    Config::USER => 'example_user',
    Config::API_KEY => 'abcdefghijklmnopqrstuvwxyz',
    Config::MODE => Config::MODE_DETECTION,
    Config::CONNECTION_FAIL => Config::ACTION_ALLOW,
    Config::IP_ADDRESS_SRC => Config::IP_ADDRESS_REMOTE_ADDR
));

//$config = file_get_contents('config.json');

$bitSensor = new BitSensor(new Config($config));

throw new Exception;