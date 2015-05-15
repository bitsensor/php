<?php
namespace BITsensor\Core\Log\SQL;


abstract class IMySqlEvent {
    private $event;

    public function __construct(ISqlError $sqlError) {
        $this->event = $sqlError;
    }

    public function GetError() {
        if ($this->event instanceof ISqlError)
            return $this->event;
        return null;
    }
}
