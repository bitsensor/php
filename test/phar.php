<?php
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;

require_once '../build/BitSensor.phar';

$config = json_encode(array(
    Config::URI => 'http://localhost/test/api/',
    Config::USER => 'example_user',
    Config::API_KEY => 'abcdefghijklmnopqrstuvwxyz'
));

//$config = file_get_contents('config.json');

$bitSensor = new BitSensor(new Config($config));

echo 'Allowed';