<?php
use BitSensor\Core\BitSensor;
use BitSensor\Core\SessionContext;

require_once 'BitSensor.phar';

global $debug;

$bitSensor = new BitSensor();

session_start();

$_SESSION['user'] = 'le moi';

$bitSensor->addContext(new SessionContext(SessionContext::USERNAME, $_SESSION['user']));

$bitSensor->addError(new \BitSensor\Core\CodeError(1, "a", "b.php", 2, null, "Custom"));
$bitSensor->addError(new \BitSensor\Core\CodeError(1, "a", "b.php", 2, null, "Custom"));

echo 'Allowed';