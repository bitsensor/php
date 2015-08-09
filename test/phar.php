<?php
use BitSensor\Core\BitSensor;

require_once '../build/BitSensor.phar';

new BitSensor('http://localhost/test/api/', 'example_user', 'abcdefghijklmnopqrstuvwxyz');

echo 'Allowed';