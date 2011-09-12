<?php
 
require_once 'AssertTest.php';
// ...
 
class FrameworkAllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');
 
        $suite->addTestSuite('Framework_AssertTest');
        // ...
 
        return $suite;
    }
}

