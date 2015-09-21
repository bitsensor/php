<?php
use BitSensor\Core\BitSensor;

require_once '../vendor/autoload.php';

global $debug;
$debug = true;

$bitSensor = new BitSensor();

throw new Exception;