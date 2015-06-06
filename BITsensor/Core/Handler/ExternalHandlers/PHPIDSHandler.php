<?php
namespace BITsensor\Core\Handler\ExternalHandlers;

use \BITsensor\Core\Context;
use \BITsensor\Core\Detection;
use \BITsensor\Core\DetectionRule;
use IDS;

class PHPIDSHandler implements IExpendableIdsHandler {
    public static function Evaluate(Context $input) {
        $init = IDS\Init::init(BITsensorBasePath . '/External/IDS/Config/Config.ini.php');
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