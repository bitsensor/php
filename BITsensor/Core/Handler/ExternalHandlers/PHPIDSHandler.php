<?php
namespace BITsensor\Core\Handler\ExternalHandlers;

include_once BITsensorBasePath . 'External/IDS/Init.php';
include_once BITsensorBasePath . 'External/IDS/Monitor.php';
include_once BITsensorBasePath . 'External/IDS/Filter/Storage.php';
include_once BITsensorBasePath . 'External/IDS/Report.php';
include_once BITsensorBasePath . 'External/IDS/Converter.php';
include_once BITsensorBasePath . 'External/IDS/Event.php';
include_once BITsensorBasePath . 'External/IDS/Caching/CacheFactory.php';
include_once BITsensorBasePath . 'External/IDS/Caching/CacheInterface.php';
include_once BITsensorBasePath . 'External/IDS/Filter.php';

use BITsensor\Core\Log\Context;
use BITsensor\Core\Log\Detection;
use BITsensor\Core\Log\DetectionRule;
use IDS;

class PHPIDSHandler implements IExpendableIdsHandler {
    public static function Evaluate(Context $input) {
        $init = IDS\Init::init(BITsensorBasePath . 'External/IDS/Config/Config.ini.php');
        $ids = new IDS\Monitor($init);
        $phpIdsReport = $ids->runOnce("", $input->value);

        if ((!$phpIdsReport->isEmpty())) {
            $detection = new Detection('PHP IDS', false);

            $phpIdsEvents = $phpIdsReport->getEvents();
            $phpIdsEvents = array_shift($phpIdsEvents)->getFilters();

            if ($phpIdsReport->getCentrifuge() != null)
                $detection->AddRule(new DetectionRule(
                    'Centrifuge',
                    'Centrifuge Meta Detection' .
                    "\n Scored " . $phpIdsReport->getCentrifuge(),
                    1,
                    array('centrifuge')));

            foreach ($phpIdsEvents as $rule) {
                /*@var $rule IDS\Filter */
                $detectionRule = new DetectionRule(
                    $rule->getId(),
                    $rule->getDescription() . $rule->getRule(),
                    1,
                    $rule->getTags()
                );

                $detection->AddRule($detectionRule);
            }

            return $detection;
        } else
            return false;
    }
}