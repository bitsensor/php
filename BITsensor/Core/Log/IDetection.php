<?php
include_once BITsensorBasePath . 'Core/Lib/Lib.php';

abstract class IDetection
{
    public $name, $callerFile;
    public $detectionRules = array();
    
    public function __construct($name = 'Anonymous', $includeCallerFilePath = true, $detectionRule = '') 
    {
        $this->name = $name;
        if($includeCallerFilePath)
            $this->callerFile = LibDebug::GetCallerFile();
        
        if(is_array($detectionRule))
            array_walk ($detectionRule, function($detectionRule) 
                {$this->AddRule($detectionRule);});
        
        if(is_a($detectionRule, 'DetectionRule') && isset($detectionRule))
            $this->AddRule ($detectionRule);
    }
    
    public function AddRule(IDetectionRule $rule)
    {
        array_push($this->detectionRules, $rule);
    }
    
    public function AddRules(array $rules)
    {
        array_walk($rules, function ($rule, $i)
            {$this->AddRule($rule);});
    }
    
    public function GetRules()
    {
        return $this->detectionRules;
    }
    
    public function HasDetection()
    {
        return !empty($this->detectionRules);
    }
} 

abstract class IDetectionRule
{
    public $name, $description, $certainty;
    public $valueContext, $valueError; 
    public $attackTypes = array(); 
    
    public function __construct($name = '0', $descripton = '', $certainty = 1, $attackTypes = null, $valueContext = null, $valueError = null) {
        $this->description = $descripton;
        $this->SetValueContext($valueContext);
        $this->SetValueError($valueError);
        $this->certainty = $certainty;
        $this->name = $name;
        
        if(isset($attackTypes)) {
            if(is_array($attackTypes)){
                foreach ($attackTypes as $attackType) {
                    $this->AddType ($attackType);
                }
            } else {
                $this->AddType ($attackTypes);
            }
        }
    }
    
    public function SetValueContext($value)
    {
        $this->valueContext = $value;
    }
    
    public function SetValueError($value)
    {
        $this->valueError = $value;
    }
    

    public function AddType($attackType)
    {
        array_push($this->attackTypes, $attackType);
    }
    
    public function AddTypes(array $types)
    {
        array_walk($types, function ($type)
            {$this->AddType($type);});
    }
}


class Detection extends IDetection {}
class DetectionRule extends IDetectionRule {}