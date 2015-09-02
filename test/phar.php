<?php
use BitSensor\Core\BitSensor;
use BitSensor\Core\SessionContext;

require_once 'BitSensor.phar';

$bitSensor = new BitSensor();

session_start();

$_SESSION['user'] = 'le moi';

$bitSensor->addContext(new SessionContext(SessionContext::USERNAME, $_SESSION['user']));

echo 'Allowed';