<?php
include_once BITsensorBasePath . 'Core/Log/ICodeError.php';

abstract class IFileIncludeError extends ICodeError {}

class FileIncludeError extends IFileIncludeError {}
