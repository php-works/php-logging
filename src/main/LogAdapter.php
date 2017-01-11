<?php

namespace Logging;

Interface LogAdapter {
    function getLog($name);
    
    function getThresholdByLogName($name);
}