<?php
use BitSensor\Core\BitSensor;

require_once '../vendor/autoload.php';

new BitSensor('http://192.168.159.131:9200/demo/datapoint/', 'example_user', 'abcdefghijklmnopqrstuvwxyz');

echo 'Allowed';