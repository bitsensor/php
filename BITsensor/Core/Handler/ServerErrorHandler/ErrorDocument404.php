<?php
namespace BITsensor\Core\Handler\ServerErrorHandler;


ServerErrorHandler::raiseServerError(404, $_SERVER['REDIRECT_URL']);