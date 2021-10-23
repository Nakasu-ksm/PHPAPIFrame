<?php

namespace API;
class Encrypt
{
    public static function encode($text, $type="md5" , $salt=""){
        switch ($type){
            case "md5":
                return md5($text);
            case "sha1":
                return sha1($text);
            case "crypt":
                return crypt($text, $salt);
        }
    }
}