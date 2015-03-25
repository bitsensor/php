<?php

interface IExpendableIdsHandler
{
    public static function Evaluate(Context $input);
}

interface IInitializableIdsHandler extends IExpendableIdsHandler
{
    public function EvaluateAll(array $input);
}