<?php
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Core\SessionContext;

require_once 'BitSensor.phar';

global $debug;
$debug = true;

$config = new Config();
$config->setUri('https://localhost:8081/');
$config->setUser('example_user');
$config->setApiKey('abcdefghijklmnopqrstuvwxyz');
$config->setMode(Config::MODE_DETECTION);
$config->setConnectionFail(Config::ACTION_ALLOW);
$config->setIpAddressSrc(Config::IP_ADDRESS_REMOTE_ADDR);
$config->setLogLevel(Config::LOG_LEVEL_ALL);

$bitSensor = new BitSensor($config);

session_start();

$_SESSION['user'] = 'le moi';

$bitSensor->addContext(new SessionContext(SessionContext::USERNAME, $_SESSION['user']));

$bitSensor->addError(new \BitSensor\Core\CodeError(1, "a", "b.php", 2, null, "Custom"));
$bitSensor->addError(new \BitSensor\Core\CodeError(1, "a", "b.php", 2, null, "Custom"));

echo 'Allowed';