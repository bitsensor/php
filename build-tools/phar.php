<?php

if (isset($argv[1])) {

    $phar = new Phar($argv[1], 0);
    $phar->buildFromDirectory('src/');
    $phar->setStub($phar->createDefaultStub('index.php'));

}