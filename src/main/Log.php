<?php

namespace Logging;

interface Log {
    const TRACE = 1;
    const DEBUG = 2;
    const INFO = 3;
    const NOTICE = 4;
    const WARN = 5;
    const ERROR = 6;
    const CRITICAL = 7;
    const ALERT = 8;
    const EMERGENCY = 9;
    const NONE = 10;
    
    function log($level, $message, array $context = null);
    
    function isEnabled($level);
    
    function trace($message, array $context = null);
    
    function debug($message, array $context = null);
    
    function info($message, array $context = null);
    
    function notice($message, array $context = null);  
    
    function warn($message, array $context = null);
    
    function error($message, array $context = null);
    
    function critical($message, array $context = null);
    
    function alert($message, array $context = null);
    
    function emergency($message, array $context = null);

    function isTraceEnabled();

    function isDebugEnabled();

    function isInfoEnabled();

    function isNoticeEnabled();

    function isWarnEnabled();

    function isErrorEnabled();

    function isCriticalEnabled();

    function isAlertEnabled();

    function isEmergencyEnabled();
}
