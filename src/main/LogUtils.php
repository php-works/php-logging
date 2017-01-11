<?php

namespace Logging;

use Exception;
use InvalidArgumentException;
use Throwable;

final class LogUtils {
    private function __construct() {
    }
    
    private static $logLevelNames = [
        Log::TRACE => 'TRACE',
        Log::DEBUG => 'DEBUG',
        Log::INFO => 'INFO',
        Log::NOTICE => 'NOTICE',
        Log::WARN => 'WARN',
        Log::ERROR => 'ERROR',
        Log::CRITICAL => 'CRITICAL',
        Log::ALERT => 'ALERT',
        Log::EMERGENCY => 'EMERGENCY',
        Log::NONE => 'NONE'
    ];
    
    static function isValidLogLevel($level, $excludeLevelNone = false) {
        return $level >= Log::TRACE && $level <= Log::EMERGENCY
            || $level === Log::NONE && !$excludeLevelNone;
    }
    
    static function getLogLevelName($level) {
        return self::$logLevelNames[$level];
    }
    
    static function formatLogMessage($message, array $context = null) {
        $ret = null;
        
        if (!is_string($message)) {
            throw new InvalidArgumentException(
                '[LogUtils::formatLogMessage] First argument $message must '
                . 'be a string');
        }
        
        if (empty($context)) {
            $ret = $message;
        } else {
            $replacements = [];
            
            foreach ($context as $key => $value) {
                $searchString = '{' . $key . '}';
                
                if (strpos($message, $searchString) !== false) {
                    $replacements[$searchString] = self::stringify($value);
                }
            }
            
            $ret = strtr($message, $replacements);
        }
        
        return $ret;
    }
    
    private static function stringify($value) {
        if ($value === null) {
            $ret = 'null';
        } else if ($value === false) {
            $ret = 'false';
        } else if ($value === true) {
            $ret = 'true';
        } else if (is_string($value)) {
            $ret = $value;
        } else if (is_scalar($value)) {
            $ret = strval($value);
        } else if ($value instanceof Exception || $value instanceof \Throwable) {
            $ret = $value->getMessage();
        } else {
            $ret = json_encode($value);
        }

        return $ret;
    }
}

