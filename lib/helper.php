<?php
//use Db;
require_once 'Removexss.php';
function json($array)
{
    header("Content-Type:application/json;charset=utf8");
    echo json_encode($array,JSON_UNESCAPED_UNICODE);
}


function success($array)
{
    header("Content-Type:application/json;charset=utf8");
    die(json_encode($array,JSON_UNESCAPED_UNICODE));
}

function error($msg, $code=501){
    throw new Exception($msg, $code);
}
function close($msg=""){
    if ($msg==""){
        error("API关闭！",1001);
    }else{
        error($msg,1001);
    }

}
/**
 * @param $key
 * @return string
 * 处理用户输入的数据
 */
function input($key)
{

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $data =  trim($_GET[$key]);
    }else{
        $data =  trim($_POST[$key]);
    }
    $data = removexss($data);
    return $data;
}

/**
 * @param mixed $data
 * @return string
 * 去除危险字符
 */
function removexss($data)
{
    $data = Removexss::get($data);
    return  $data;
}


function null($check,$type)
{
    if (empty($check))
    {
        throw new Exception("{$type}不能为空",502);
    }
}

function db($table=''){
    if ($table=='') {
        exit(json_encode(['status'=>0,'message'=>'这个表不存在']));
    }
    $db=new Db();
    return $db->table($table);
}

function no_empty($key, $msg){
    if (empty($key)){
        error($msg);
    }
}

function return_json($data, $code){
    json(array(
        'code'=>$code,
        'data'=>$data
    ));
}

function generate_key($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


/**
 * @param string $string 原文或者密文
 * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
 * @param string $key 密钥
 * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
 *
 * @example
 *
 * $a = authcode('abc', 'ENCODE', 'key');
 * $b = authcode($a, 'DECODE', 'key'); // $b(abc)
 *
 * $a = authcode('abc', 'ENCODE', 'key', 3600);
 * $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */









