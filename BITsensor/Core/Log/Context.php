<?php
namespace BITsensor\Core\Log;


class Context extends IContext {
    public static function User($identifier, $applicationName = 'Application') {
        return new Context(array($applicationName, 'User'), $identifier);
    }

    public static function Session($identifier, $applicationName = 'Application') {
        return new Context(array($applicationName, 'Session'), $identifier);
    }
}