<?php
namespace BITsensor\Core;


abstract class IDetectionRule {
    public $name, $description, $certainty;
    public $valueContext, $valueError;
    public $attackTypes = array();

    public function __construct($name = '0', $descripton = '', $certainty = 1, $attackTypes = null, $valueContext = null, $valueError = null) {
        $this->description = $descripton;
        $this->SetValueContext($valueContext);
        $this->SetValueError($valueError);
        $this->certainty = $certainty;
        $this->name = $name;

        if (isset($attackTypes)) {
            if (is_array($attackTypes)) {
                foreach ($attackTypes as $attackType) {
                    $this->AddType($attackType);
                }
            } else {
                $this->AddType($attackTypes);
            }
        }
    }

    public function SetValueContext($value) {
        $this->valueContext = $value;
    }

    public function SetValueError($value) {
        $this->valueError = $value;
    }


    public function AddType($attackType) {
        array_push($this->attackTypes, $attackType);
    }

    public function AddTypes(array $types) {
        array_walk($types, function ($type) {
            $this->AddType($type);
        });
    }
}