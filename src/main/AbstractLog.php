<?php

namespace Logging;

use Exception;
use InvalidArgumentException;
use Throwable;

abstract class AbstractLog implements Log {
    abstract function log($level, $message, array $context = null);
    
    abstract function isEnabled($level);
    
    final function trace($message, array $context = null) {
        $this->performLogging(Log::TRACE, $message, $context);   
    }
    
    final function debug($message, array $context = null) {
        $this->performLogging(Log::DEBUG, $message, $context);   
    }
    
    final function info($message, array $context = null) {
        $this->performLogging(Log::INFO, $message, $context);   
    }
    
    final function notice($message, array $context = null) {
        $this->performLogging(Log::TRACE, $message, $context);   
    }
    
    final function warn($message, array $context = null) {
        $this->performLogging(Log::WARN, $message, $context);
    }
    
    final function error($message, array $context = null) {
        $this->performLogging(Log::ERROR, $message, $context);
    }
    
    final function critical($message, array $context = null) {
        $this->performLogging(Log::CRITICAL, $message, $context);
    }
    
    final function alert($message, array $context = null) {
        $this->performLogging(Log::ALERT, $message, $args, $extra);
    }
    
    final function emergency($message = null, array $context = null) {
        $this->performLogging(Log::EMERGENCY, $message, $context);
    }

    final function isTraceEnabled() {
        return $this->isEnabled(Log::TRACE);   
    }

    final function isDebugEnabled() {
        return $this->isEnabled(Log::DEBUG);
    }

    final function isInfoEnabled() {
        return $this->isEnabled(Log::INFO);
    }

    final function isNoticeEnabled() {
        return $this->isEnabled(Log::NOTICE);
    }

    final function isWarnEnabled() {
        return $this->isEnabled(Log::WARN);
    }

    final function isErrorEnabled() {
        return $this->isEnabled(Log::ERROR);
    }

    final function isCriticalEnabled() {
        return $this->isEnabled(Log::CRITICAL);
    }

    final function isAlertEnabled() {
        return $this->isEnabled(Log::ALERT);
    }

    final function isEmergencyEnabled() {
        return $this->isEnabled(Log::EMERGENCY);
    }
    
    private function performLogging($level, $message, array $context = null) {
        // no need to validate $level
        $error = null; 
        
        if (!is_string($message)) {
            $error = 'First argument $message must be a string';
        }
        
        if ($error !== null) {
            $levelName = LogUtils::getLogLevelName($level);
            $methodName = strtolower($levelName); 
            $className = preg_replace('/^([^\\\\]+\\\\)+/', '', get_class($this));
            
            throw new InvalidArgumentException("[$className#$methodName] $error");
        } 
            
        $this->log($level, $message, $context);
    } 
}
