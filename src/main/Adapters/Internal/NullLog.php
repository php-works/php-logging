<?php

namespace Logging\Adapters\Internal;

use InvalidArgumentException;
use Throwable;
use Logging\AbstractLog;
use Logging\LogUtils;

class NullLog extends AbstractLog {
    function log($level, $message, array $context = null) {
        if (!LogUtils::isValidLogLevel($level, true)) {
            throw new InvalidArgumentException(
                '[NullLog::log] First argument $level must be a '
                . 'valid log level');
        }
    }
    
    function isEnabled($level) {
        return false;
    }
}
