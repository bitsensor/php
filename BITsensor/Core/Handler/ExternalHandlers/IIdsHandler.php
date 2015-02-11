<?php

interface IExpendableIdsHandler
{
    public static function Evaluate(IRequestInput $input);
}

interface IInitializableIdsHandler extends IExpendableIdsHandler
{
    public function EvaluateAll(array $input);
}