<?php

namespace Logging\Adapters\Internal;

use InvalidArgumentException;
use Logging\AbstractLog;
use Logging\Log;
use Logging\Logger;
use Logging\LogUtils;

class CustomLog extends AbstractLog {
    private $stream;

    function __construct(
        $name,
        callable $performLogRequest,
        callable $getThresholdByLogName = null) {
        
        $this->name = $name;
        $this->performLogRequest = $performLogRequest;
        $this->getThresholdByLogName = $getThresholdByLogName;
    }
    
    function log($level, $message, array $context = null) {
        $date = date('Y-m-d H:i:s');
        $levelName = LogUtils::getLogLevelName($level);
        $text = LogUtils::formatLogMessage($message, $context); 
        $name = $this->name;
        
        $performLogRequest = $this->performLogRequest;
        
        $performLogRequest([
            'date' => $date,
            'name' => $name,
            'level' => $levelName,
            'code' => $level,
            'message' => $text
        ]);
    }
    
    function isEnabled($level) {
        $ret = false;
        
        if ($this->getThresholdByLogName !== null) {
            $getThresholdByLogName = $this->getThresholdByLogName;
            
            $ret = $getThresholdByLogName($this->name);
        } else {
            $ret = $level >= Logger::getDefaultThreshold();
        }
        
        return $ret;
    }
}
