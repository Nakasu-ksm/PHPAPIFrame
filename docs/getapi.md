# 创建第一个API模块
本API提供了一个参考class 在目录

/class/Test.php 里面

如果您需要创建一个新的API文件，请在class目录下创建一个Xxxx.php （Xxxx为你想要的名字，注意开头必须大写）

您创建的API里面必须包含run这个公共变量，如下
```php
 public function run()
    {
        //内容
    }
```


**建议您建设的API遵循**

**第一步得到数据，第二步验证数据，第三步进行内部操作(安全方法)**
```php
public function run()
    {
        $this->_get_data();
        $this->_check();
        $this->_start();
    }
```



