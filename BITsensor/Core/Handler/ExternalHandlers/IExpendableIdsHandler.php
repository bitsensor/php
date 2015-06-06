<?php
namespace BITsensor\Core\Handler\ExternalHandlers;


use \BITsensor\Core\Context;

interface IExpendableIdsHandler {
    public static function Evaluate(Context $input);
}