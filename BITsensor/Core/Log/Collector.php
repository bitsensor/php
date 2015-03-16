<?php
include_once 'IRequest.php';
include_once 'IError.php';
include_once 'IRequestInput.php';
include_once 'IContext.php';
include_once 'SQL/IMySqlEvent.php';
include_once 'IDetection.php';
include_once BITsensorBasePath . 'Core/Handler/ExternalHandlers/PHPIDSHandler.php';
include_once BITsensorBasePath . 'Core/Handler/DetectionHandler.php';

class Collector {
    private $contextCollection = array();
    
    private $errorCollection = array(), $inputCollection = array(), $detectionCollection = array();
    
    private $requestInputProcessed = false;
    private $requestContextProcessed = false;
    
    public function SetInputProcessed($value)
    {
        $this->requestInputProcessed = $value;
        $this->checkRequestProcessed();
    }
    
    public function SetContextProcessed($value)
    {
        $this->requestContextProcessed = $value;
        $this->checkRequestProcessed();
    }
    
    private function checkRequestProcessed()
    {
        if($this->requestContextProcessed && $this->requestInputProcessed) {
            if(!empty($this->detectionCollection)) {
                DetectionHandler::Handle();
            }
        }
    }

    public function AddContext(IContext $context)
    {
        $this->_addContext($context);
    }
    
    public function DeleteContext($naem)
    {
        $this->_deleteContext($naem);
    }

    public function AddInput(IRequestInput $input)
    {
        $this->_addInput($input);
    }
    
    public function AddInputSet($input)
    {
        array_walk($input, 
            function ($value, $key) {$this->_addInput($value);});
    }
    
    public function AddDetection($detection) {
        $this->_addDetectioin($detection);
    }

    public function AddError(IError $error)
    {
        $this->_addError(new Context(get_class($error), $error));
    }

    private function _addContext($object)
    {
        array_push($this->contextCollection, $object);
    }
    
    private function _deleteContext($name)
    {
        for($n = 0; $n < count($this->contextCollection); $n++) {
            if($this->contextCollection[$n]->name === $name)
                unset ($this->contextCollection[$n]);
        }
    }

    private function _addInput(IRequestInput $object)
    {
        array_push($this->inputCollection, $object);
        
        if ($object->value == NULL)
            return;
        
        $IdsProcessor = PHPIDSHandler::Evaluate($object);
        if($IdsProcessor->HasDetection()) 
        {
            $detection = $IdsProcessor;
            
            foreach($IdsProcessor->GetRules() as $detectionRule)
            {
                $detectionRule->SetInput($object);
                $detection->AddRule($detectionRule);
            }
            
            $this->AddDetection($detection);
        }
    }
    
    private function _addError($object)
    {
        array_push($this->errorCollection, $object);   
    }
    
    private function _addDetectioin($object)
    {
        array_push($this->detectionCollection, $object);
        $this->checkRequestProcessed();
    }
    
    private function _getCollections()
    {
        $serializableCollector = new stdClass;
        $serializableCollector->Detections = $this->detectionCollection;
        $serializableCollector->Errors = $this->errorCollection;
        $serializableCollector->Input = $this->inputCollection;
        $serializableCollector->Context = $this->contextCollection;
        return $serializableCollector;
//        
//        return array (
//
//            
//            new Context('Detection', $this->detectionCollection),
//            new Context('Error', $this->errorCollection),
//            new Context('Input', $this->inputCollection),
//            new Context('Context', $this->contextCollection)
//        );
    }
    
    public function Get()
    {
        return $this->_getCollections();
    }

    public function Serialize($prettyPrint = false)
    {
        return $prettyPrint ? json_encode($this->_getCollections(), JSON_PRETTY_PRINT ) : json_encode($this->_getCollections());
    }
}