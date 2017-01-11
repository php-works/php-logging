<?php

namespace Logging\Adapters;

use Logging\LogAdapter;
use Logging\Adapters\Internal\NullLog;

class NullLogAdapter implements LogAdapter {
    private $log;

    function __construct() {
        $this->log = new NullLog();
    }
    
    function getLog($name) {
        return $this->log;
    }

    function getThresholdByLogName($name) {
        return LOG::NONE;
    }
}
