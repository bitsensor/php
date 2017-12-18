<?php

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Core\SessionContext;

require_once __DIR__ . '/../target/bitsensor.phar';

global $debug;
$debug = true;

$config = new Config();
$config->setUri('http://localhost:8080/');
$config->setUser('example_user');
$config->setApiKey('abcdefghijklmnopqrstuvwxyz');
$config->setMode(Config::MODE_DETECTION);
$config->setConnectionFail(Config::ACTION_ALLOW);
$config->setIpAddressSrc(Config::IP_ADDRESS_REMOTE_ADDR);
$config->setHostSrc(Config::HOST_SERVER_NAME);
$config->setLogLevel(Config::LOG_LEVEL_ALL);

$bitSensor = new BitSensor();
$bitSensor->config($config);

session_start();

$_SESSION['user'] = 'le moi';

$bitSensor->putContext('php.' . SessionContext::NAME . '.' . SessionContext::USERNAME, $_SESSION['user']);

$error = new \Proto\Error();
$error->setCode(1);
$error->setDescription('a');
$error->setLocation('b.php');
$error->setLine(2);
$error->setType('Custom');

$bitSensor->addError($error);
$bitSensor->addError($error);


