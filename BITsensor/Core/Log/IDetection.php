<?php
namespace BITsensor\Core\Log;


use BITsensor\Core\Lib\LibDebug;

abstract class IDetection {
    public $name, $callerFile;
    public $detectionRules = array();

    public function __construct($name = 'Anonymous', $includeCallerFilePath = true, $detectionRule = '') {
        $this->name = $name;
        if ($includeCallerFilePath)
            $this->callerFile = LibDebug::GetCallerFile();

        if (is_array($detectionRule))
            array_walk($detectionRule, function ($detectionRule) {
                $this->AddRule($detectionRule);
            });

        if (is_a($detectionRule, 'DetectionRule') && isset($detectionRule))
            $this->AddRule($detectionRule);
    }

    public function AddRule(IDetectionRule $rule) {
        array_push($this->detectionRules, $rule);
    }

    public function AddRules(array $rules) {
        array_walk($rules, function ($rule, $i) {
            $this->AddRule($rule);
        });
    }

    public function GetRules() {
        return $this->detectionRules;
    }

    public function HasDetection() {
        return !empty($this->detectionRules);
    }
}