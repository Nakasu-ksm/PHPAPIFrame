<?php
namespace API;
class Date
{
    public static function test(){
        var_dump("var");
    }
    public static function getInstance()
    {
        self::$instance || self::$instance = new self();
        return self::$instance;
    }
}