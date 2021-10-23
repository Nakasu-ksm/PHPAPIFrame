<?php
    require 'Config.php';
    function temp_pdo()
    {
        return new PDO("mysql:host=".HOST.";dbname=".DBNAME,USERNAME,PASSWORD);

    }





