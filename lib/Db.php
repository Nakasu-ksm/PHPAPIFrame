<?php


class Db
{
    private $_db = null;//数据库连接句柄
    private $_table = null;//表名
    private $_where = null;//where条件
    private $_order = null;//order排序
    private $_limit = null;//limit限定查询
    private $_group = null;//group分组
    private static $pdo = null;

    public function __construct()
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }else{
            self::$pdo = new PDO(
                "mysql:host=127.0.0.1;dbname=api_database",//设置服务器地址和数据库名称
                'api_database',// 数据库账户
                'JD4BpZ4Xn5LGGaZs',// 数据库密码
                array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC)
            );
           $this->query("set names utf8");
        }
    }
    /**
     * 执行sql语句查询
     */
    public function query($sql){
        $sth = self::$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $sth;
    }
    /**
     * 获取所有数据
     *
     * @param      <type>   $table  The table
     *
     * @return     boolean  All.
     */
    public function getAll($table=null){
        $sql = "SELECT * FROM $table";
        $sth = self::$pdo->prepare($sql);
        $sth->execute();

        return $sth->fetchAll();
    }

    public function table($table){
        $this->_table = $table;
        return $this;
    }
    /**
     * 实现查询单条记录操作
     *
     * @param      string   $fields  The fields
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function get_one($fields="*"){
        $fieldsStr = '';
        if(is_array($fields)){
            $fieldsStr = implode(',', $fields);
        }elseif(is_string($fields)&&!empty($fields)){
            $fieldsStr = $fields;
        }
        $sql = "SELECT {$fields} FROM {$this->_table} {$this->_where}";
        $sth = self::$pdo->prepare($sql);
        $sth->execute();
        return $sth->fetch();
    }
    /**
     * 实现查询操作
     *
     * @param      string   $fields  The fields
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function select($fields="*"){
        $fieldsStr = '';
        if(is_array($fields)){
            $fieldsStr = implode(',', $fields);
        }elseif(is_string($fields)&&!empty($fields)){
            $fieldsStr = $fields;
        }
        $sql = "SELECT {$fields} FROM {$this->_table} {$this->_where} {$this->_order} {$this->_limit}";
        $sth = self::$pdo->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();
    }
    /**
     * 释放查询条件
     */
    public function release(){
        $this->_table = null;//表名
        $this->_where = null;//where条件
        $this->_order = null;//order排序
        $this->_limit = null;//limit限定查询
        $this->_group = null;//group分组
    }
    /**
     * 查询条件拼接，使用方式：
     *
     * $this->where(['id = 1','and title="Web"', ...])->fetch();
     * 为防止注入，建议通过$param方式传入参数：
     * $this->where(['id = :id'], [':id' => $id])->fetch();
     *
     * @param array $where 条件
     * @return $this 当前对象
     */
    public function where($where='')
    {
        $whereStr = '';
        if(is_array($where)){
            foreach ($where as $key => $value) {
                if($value == end($where)){
                    $whereStr .= "`".$key."` = '".$value."'";
                }else{
                    $whereStr .= "`".$key."` = '".$value."' AND ";
                }
            }
            $whereStr = "WHERE ".$whereStr;
        }elseif(is_string($where)&&!empty($where)){
            $whereStr = "WHERE ".$where;
        }
        $this->_where = $whereStr;
        return $this;
    }

    /**
     * 拼装排序条件，使用方式：
     *
     * $this->order(['id DESC', 'title ASC', ...])->fetch();
     *
     * @param array $order 排序条件
     * @return $this
     */
    public function order($order='')
    {
        $orderStr = '';
        if(is_string($order)&&!empty($order)){
            $orderStr = "ORDER BY ".$order;
        }
        $this->_order = $orderStr;
        return $this;
    }
    /**
     * group分组
     *
     * @param      string   $group  The group
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function group($group=''){
        $groupStr = '';
        if(is_array($group)){
            $groupStr = "GROUP BY ".implode(',',$group);
        }elseif(is_string($group)&&!empty($group)){
            $groupStr = "GROUP BY ".$group;
        }
        $this->_group = $groupStr;
        return $this;
    }
    /**
     * limit限定查询
     *
     * @param      string  $limit  The limit
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function limit($limit=''){
        $limitStr = '';
        if(is_string($limit)||!empty($limit)){
            $limitStr = "LIMIT ".$limit;
        }elseif(is_numeric($limit)){
            $limitStr = "LIMIT ".$limit;
        }
        $this->_limit = $limitStr;
        return $this;
    }

    /**
     * 插入数据
     *
     * @param      <type>   $data   The data
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function insert($data){
        if(is_array($data)){
            $keys = '';
            $values = '';
            foreach ($data as $key => $value) {
                $keys .= "`".$key."`,";
                $values .= "'".$value."',";
            }
            $keys = rtrim($keys,',');
            $values = rtrim($values,',');
        }
        $sql = "INSERT INTO `{$this->_table}`({$keys}) VALUES({$values})";
        $pdo = self::$pdo;
        $sth = $pdo->prepare($sql);
        $sth->execute();
        return $pdo->lastInsertId();
    }

    /**
     * 更新数据
     *
     * @param      <type>  $data   The data
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function update($data){
        if(is_array($data)){
            $dataStr = '';
            foreach ($data as $key => $value) {
                $dataStr .= "`".$key."`='".$value."',";
            }
            $dataStr = rtrim($dataStr,',');
        }
        $sql = "UPDATE `{$this->_table}` SET {$dataStr} {$this->_where} {$this->_order} {$this->_limit}";
        $sth = self::$pdo->prepare($sql);
        $sth->execute();
       // if (empty($sth->rowCount()))
        //{
        //    throw new Exception('记录不存在','501');
       // }
        return $sth->rowCount();
    }

    /**
     * 删除数据
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function delete(){
        $sql = "DELETE FROM `{$this->_table}` {$this->_where}";
        $sth = self::$pdo->prepare($sql);
        $sth->execute();
        return $sth->rowCount();
    }
    /**
     * 异常信息输出
     *
     * @param      <type>  $var    The variable
     */
    private function ShowException($var){
        if(is_bool($var)){
            var_dump($var);
        }else if(is_null($var)){
            var_dump(NULL);
        }else{
            echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>".print_r($var,true)."</pre>";
        }
    }
}