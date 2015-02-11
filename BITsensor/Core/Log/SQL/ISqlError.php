<?php
include_once BITsensorBasePath . 'Core/Log/ICodeError.php';

abstract class ISqlError extends ICodeError {}

class SqlError extends ISqlError {}