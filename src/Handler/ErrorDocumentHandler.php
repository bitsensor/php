<?php

use BitSensor\Core\ApacheError;
use BitSensor\Core\BitSensor;
use BitSensor\View\ErrorView;

require_once '../index.php';

if (isset($_GET['e'])) {
    $path = getenv('ERROR_DOCUMENT_' . $_GET['e']);
    if ($path) {
        ob_start();
        $loaded = virtual($path);

        if (!$loaded) {
            ob_end_clean();

            $view = new ErrorView($_GET['e']);
            $view->show();
        } else {
            ob_end_flush();
        }
    } else {
        $view = new ErrorView($_GET['e']);
        $view->show();
    }
}

$bitSensor = new BitSensor();

$bitSensor->addError(new ApacheError($_GET['e'], null, 'ServerError'));