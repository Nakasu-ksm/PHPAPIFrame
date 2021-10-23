<?php
namespace API;
class Json
{
    private function jsonReturn(){

    }
    public static function toJson($arr){
        return json_encode($arr);
    }
    public static function toArr($json){
        return json_decode($json, true);
    }
    public static function toObject($json){
        return json_decode($json);
    }

}