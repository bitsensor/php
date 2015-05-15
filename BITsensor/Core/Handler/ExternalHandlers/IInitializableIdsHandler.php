<?php
namespace BITsensor\Core\Handler\ExternalHandlers;


interface IInitializableIdsHandler extends IExpendableIdsHandler {
    public function EvaluateAll(array $input);
}