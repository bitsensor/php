<?php

include 'ServerErrorHandler.php';
raiseServerError(505, $errStr, $_SERVER['REDIRECT_URL']);