<?php

use BitSensor\Core\ApacheError;
use BitSensor\Core\BitSensor;

echo '<h1>' . $_GET['e'] . '</h1>';

require_once '../index.php';

$bitSensor = new BitSensor();

$bitSensor->addError(new ApacheError($_GET['e'], null, 'ServerError'));