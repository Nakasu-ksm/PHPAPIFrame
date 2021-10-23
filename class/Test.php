<?php
use API\Date;
class Test
{
    private $cleanstring;
    //run为自动执行函数，需设置public
    public function run()
    {
        Date::test();
        $this->_get_data();
        $this->_check();
        $this->_start();
    }

    private function _get_data(){
        $this->cleanstring = input("name");
    }
    private function _check(){
        no_empty($this->cleanstring, "name不可为空");
    }
    private function _start(){
        success(array(
            "code"=>200,
            "message"=>"你的名字是".$this->cleanstring
        ));
    }
}