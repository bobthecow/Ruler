<?php

require_once 'vendor/autoload.php';

if(class_exists('PHPUnit_Framework_Error_Deprecated')) {
    class_alias('PHPUnit_Framework_Error_Deprecated', 'PHPUnit\Framework\Error\Deprecated');
}
