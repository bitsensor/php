<?php

use BitSensor\Core\ApacheError;
use BitSensor\Core\BitSensor;

require_once '../index.php';

if (isset($_GET['e'])) {
    $path = getenv('ERROR_DOCUMENT_' . $_GET['e']);
    if ($path && file_exists($path)) {
        /** @noinspection PhpIncludeInspection */
        include $path;
    } else {
        echo '<h1>' . $_GET['e'] . '</h1>';
    }
}

$bitSensor = new BitSensor();

$bitSensor->addError(new ApacheError($_GET['e'], null, 'ServerError'));