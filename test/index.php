<?php
use BitSensor\Core\BitSensor;

require_once '../vendor/autoload.php';

header('Content-Type: application/json');

new BitSensor('http://localhost/test/api/', 'example_user', 'abcdefghijklmnopqrstuvwxyz');

echo 'Allowed';