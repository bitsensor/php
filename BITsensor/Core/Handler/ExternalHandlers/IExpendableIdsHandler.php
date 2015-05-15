<?php
namespace BITsensor\Core\Handler\ExternalHandlers;


use BITsensor\Core\Log\Context;

interface IExpendableIdsHandler {
    public static function Evaluate(Context $input);
}