<?php
include_once BITsensorBasePath . 'Core/Lib/Lib.php';

abstract class IDetection
{
    public $name, $idsUId, $callerFile, $attackType;
    public $detectionRules = array();
    
    public function __construct($name = 'Anonymous', $idsUid = '00000000-0000-0000-0000-000000000000', $includeCallerFilePath = true, $detectionRule = '') 
    {
        $this->name = $name;
        $this->idsUId = $idsUid;
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
    public $description, $value, $certainty, $id;
    public $attackTypes = array(); 
    
    public function __construct($descripton, $value = '', $certainty = 1, $id = '0', $attackTypes = '') {
        $this->description = $descripton;
        $this->value = $value;
        $this->certainty = $certainty;
        $this->id = $id;
        
        if(is_array($attackTypes)){
            foreach ($attackTypes as $attackType) {
                $this->AddType (new AttackType ($attackType));
            }
        }
            
        if(is_string($attackTypes) && isset($attackTypes))
            $this->AddType (new AttackType($attackTypes));
    }
    
    public function SetInput($value)
    {
        $this->value = $value;
    }

    public function AddType(IAttackType $attackType)
    {
        array_push($this->attackTypes, $attackType);
    }
    
    public function AddTypes(array $types)
    {
        array_walk($types, function ($type, $i)
            {$this->AddType($type);});
    }
}

class IAttackType
{
    public function __construct($name) {
        $this->name = $name;
    }
    public $name;
}

class Detection extends IDetection {}
class DetectionRule extends IDetectionRule {}
class AttackType extends IAttackType {}