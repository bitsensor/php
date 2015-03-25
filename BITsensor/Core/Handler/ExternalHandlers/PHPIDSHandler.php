<?php
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
    public static function Evaluate(Context $input)
    {
        $init = IDS\Init::init(BITsensorBasePath . 'External/PHPIDS/Config/Config.ini.php');
        $ids = new IDS\Monitor($init);
        $phpIdsReport = $ids->runOnce ("", $input->value);
        
        if((!$phpIdsReport->isEmpty()))
        {
            $detection = new Detection('PHP IDS', false);
            
            $phpIdsEvents = $phpIdsReport->getEvents();
            $phpIdsEvents = array_shift($phpIdsEvents)->getFilters();
            
            if ($phpIdsReport->getCentrifuge() != null)
                $detection->AddRule (new DetectionRulele(
                        'Centrifuge', 
                        'Centrifuge Meta Detection' . 
                            "\n Scored " . $phpIdsReport->getCentrifuge(), 
                        1, 
                        array('centrifuge')));
            
            foreach($phpIdsEvents as $rule)
            {    
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
        }
        else
            return false;
    }
}