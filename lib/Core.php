<?php
require '../vendor/autoload.php';
require 'Config.php';
require 'database.php';
require 'helper.php';
use QL\QueryList;

class Core
{
    public $core;

    //private $_Open_API = ['video','weather','checkin','doraemon_hitokoto','translate','study_bot'];
    /**
     * Index constructor.
     * @param $_Test
     */

    public function __construct()
    {

    }

    private $_get_path; //获取访问路径
    private $_support_method = ['POST','GET'];


    private function get_path()
    {
        $this->_get_path = explode('/',$_SERVER["REQUEST_URI"]);
        if (strstr($this->_get_path[2],"?"))
        {
            $this->_get_path[2] = substr($this->_get_path[2],0,strpos($this->_get_path[2], '?'));
        }
        //var_dump($_SERVER);
    }

    private function Check()
    {
        if ($this->_get_path['1']!=API_VISION)
        {
            throw new Exception("请求API版本错误",301);
        }
        if (!in_array($_SERVER["REQUEST_METHOD"],$this->_support_method))
        {
            throw new Exception("请求类型错误!",403);
        }
//        if (!$this->_get_path[2] or !in_array($this->_get_path[2],$this->_Open_API))
//        {
//            throw new Exception("请求地址错误!",502);
//        }
        if (!$this->_get_path[2])
            {
                throw new Exception("请求地址错误!",502);
            }
    }
    private function Get_class()
    {
        $this->_get_path[2] = ucfirst($this->_get_path[2]);
        require "../lib/Db.php";
        if (!file_exists("../class/{$this->_get_path[2]}.php"))
        {
            error("API访问错误");
        }

        require "../class/{$this->_get_path[2]}.php";

        $Run = new $this->_get_path[2]();
        $Run->core = $this->core;
        $Run->run();

    }


    public function run()
    {
        try {

            $this->get_path();
            $this->Check();
            $this->Get_class();

        }catch (Exception $e)
        {
            $check_array = unserialize($e->getMessage());
            if (is_array($check_array))
            {
                $message = unserialize($e->getMessage());


            }else{
                $message = $e->getMessage();
            }
            json(array(
                "code"=>$e->getCode(),
                'message'=>$message,
            ));
        }
    }


}

$ce = new Core();
$ce->core = $ce;
$ce->run();