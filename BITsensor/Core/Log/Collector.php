<?php
namespace BITsensor\Core\Log;

use BITsensor\Core\Handler\ExternalHandlers\PHPIDSHandler;
use stdClass;

class Collector {
    private $contextCollection = array(), $errorCollection = array(), $inputCollection = array(), $detectionCollection = array();

    private $requestInputProcessed = false;
    private $requestContextProcessed = false;

    public function SetInputProcessed($value) {
        $this->requestInputProcessed = $value;
        $this->checkRequestProcessed();
    }

    public function SetContextProcessed($value) {
        $this->requestContextProcessed = $value;
        $this->checkRequestProcessed();
    }

    private function checkRequestProcessed() {
        if ($this->requestContextProcessed && $this->requestInputProcessed) {
            if (!empty($this->detectionCollection)) {
                //DetectionHandler::Handle();
            }
        }
    }

    public function AddContext(IContext $context) {
        $this->_addContext($context);
    }

    public function DeleteContext($naem) {
        $this->_deleteContext($naem);
    }

    public function AddInput(Context $input) {
        $this->_addInput($input);
    }

    public function AddInputSet($input) {
        array_walk($input,
            function ($value, $key) {
                $this->_addInput($value);
            });
    }

    public function AddDetection($detection) {
        $this->_addDetectioin($detection);
    }

    public function AddError(IError $error) {
        $error->type = get_class($error);
        $this->_addError($error);
    }

    private function _addContext(IContext $context) {
        if (!isset($context))
            return;

        if (is_array($context->value)) {
            while ($value = array_pop($context->value)) {
                array_push($this->contextCollection, Collector::_linearContext($context, $value));
            }
        } else {
            array_push($this->contextCollection, Collector::_linearContext($context, $context->value));
        }

    }

    private static function _linearContext($context, $value) {
        if (gettype($value) == 'object' && in_array('IContext', class_parents($value))) {
            $appendedName = array();
            if (is_array($context->name))
                foreach ($context->name as $key)
                    array_push($appendedName, $key);
            else
                array_push($appendedName, $context->name);

            array_push($appendedName, $value->name);

            $value->setName($appendedName);
            return $value;
        } else {
            return $context;
        }
    }

    private function _deleteContext($name) {
        for ($n = 0; $n < count($this->contextCollection); $n++) {
            if ($this->contextCollection[$n]->name === $name)
                unset ($this->contextCollection[$n]);
        }
    }

    private function _addInput(Context $object) {
        array_push($this->inputCollection, $object);

        if ($object->value == NULL)
            return;

        $IdsProcessor = PHPIDSHandler::Evaluate($object);
        if ($IdsProcessor->HasDetection()) {
            $detection = $IdsProcessor;

            foreach ($IdsProcessor->GetRules() as $detectionRule) {
                $detectionRule->SetValueContext($object);
                $detection->AddRule($detectionRule);
            }

            $this->AddDetection($detection);
        }
    }

    private function _addError($object) {
        array_push($this->errorCollection, $object);
    }

    private function _addDetectioin($object) {
        array_push($this->detectionCollection, $object);
        $this->checkRequestProcessed();
    }

    private function _getCollections() {
        $serializableCollector = new stdClass;
        $serializableCollector->Detections = $this->detectionCollection;
        $serializableCollector->Errors = $this->errorCollection;
        $serializableCollector->Input = $this->inputCollection;
        $serializableCollector->Context = $this->contextCollection;
        return $serializableCollector;
    }

    public function Get() {
        return $this->_getCollections();
    }

    public function Serialize($prettyPrint = false) {
        return $prettyPrint ? json_encode($this->_getCollections(), JSON_PRETTY_PRINT) : json_encode($this->_getCollections());
    }
}