<?php
include_once BITsensorBasePath . 'Core/Log/IRequestInput.php';
include_once BITsensorBasePath . 'External/PHPIDS/Init.php';
include_once BITsensorBasePath . 'External/PHPIDS/Monitor.php';
include_once BITsensorBasePath . 'External/PHPIDS/Filter/Storage.php';
include_once BITsensorBasePath . 'External/PHPIDS/Report.php';
include_once BITsensorBasePath . 'External/PHPIDS/Converter.php';
include_once BITsensorBasePath . 'External/PHPIDS/Event.php';
include_once BITsensorBasePath . 'External/PHPIDS/Caching/CacheFactory.php';
include_once BITsensorBasePath . 'External/PHPIDS/Caching/CacheInterface.php';
include_once BITsensorBasePath . 'External/PHPIDS/Filter.php';
include_once 'IIdsHandler.php'; 

class PHPIDSHandler implements IExpendableIdsHandler
{
    public static function Evaluate(IRequestInput $input)
    {
        $init = IDS\Init::init(BITsensorBasePath . 'External/PHPIDS/Config/Config.ini.php');
        $ids = new IDS\Monitor($init);
        $phpIdsReport = $ids->runOnce ("", $input->value);
        
        if((!$phpIdsReport->isEmpty()))
        {
            $detection = new Detection('PHP IDS', '2bc90c78-c06b-4b83-adf0-25005baed938', false);
            
            $phpIdsEvents = $phpIdsReport->getEvents();
            $phpIdsEvents = array_shift($phpIdsEvents)->getFilters();
            
            if ($phpIdsReport->getCentrifuge() != null)
                $detection->AddRule (new DetectionRulele('Centrifuge Meta Detection' . "\n Scored " . $phpIdsReport->getCentrifuge(), 1, 'Centrifuge' ));
            
            foreach($phpIdsEvents as $rule)
            {    
                /*@var $rule IDS\Filter */
                $detectionRule = new DetectionRule(
                                    $rule->getDescription() . 
                                    $rule->getRule(),
                                    null,
                                    $rule->getImpact(),
                                    $rule->getId(),
                                    $rule->getTags() 
                        );
                
                $detection->AddRule($detectionRule);
            }
            
            //$phpIdsReport->getCentrifuge();
            return $detection;
        }
        else
            return false;
    }
}