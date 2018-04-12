<?php

use BitSensor\Connector\ApiConnector;
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Core\SessionContext;

require __DIR__ . '../vendor/autoload.php';

global $debug;
$debug = true;

$config = new Config();
$config->setConnector('api');
ApiConnector::setUser('dev');
ApiConnector::setApiKey('php-plugin-debug');
$config->setMode(Config::MODE_DETECTION);
$config->setIpAddressSrc(Config::IP_ADDRESS_REMOTE_ADDR);
$config->setHostSrc(Config::HOST_SERVER_NAME);
$config->setLogLevel(Config::LOG_LEVEL_ALL);
BitSensor::configure($config);

session_start();

$_SESSION['user'] = 'le moi';

BitSensor::putContext('php.' . SessionContext::NAME . '.' . SessionContext::USERNAME, $_SESSION['user']);

$error = new \Proto\Error();
$error->setCode(1);
$error->setDescription('a');
$error->setLocation('b.php');
$error->setLine(2);
$error->setType('Custom');

BitSensor::addError($error);