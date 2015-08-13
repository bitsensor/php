<?php
use BitSensor\Core\BitSensor;

require_once '../vendor/autoload.php';

global $debug;

new BitSensor('http://localhost/test/api/', 'example_user', 'abcdefghijklmnopqrstuvwxyz');

trigger_error('Test Error');

function test() {
    throw new Exception('Test Exception');
}

echo 'Allowed';

test();

echo 'Exception thrown';