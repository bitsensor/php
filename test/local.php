<?php
use BitSensor\Core\BitSensor;

require_once '../vendor/autoload.php';

global $debug;

new BitSensor('http://localhost/test/api/', 'example_user', 'abcdefghijklmnopqrstuvwxyz');

echo 'Allowed';