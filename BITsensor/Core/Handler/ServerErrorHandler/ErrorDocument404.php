<?php
include 'ServerErrorHandler.php';
raiseServerError(404, $_SERVER['REDIRECT_URL']);