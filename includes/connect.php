<?php
    if(!defined('_INCODE')) die('Access Denied...');

    require 'config.php';
    try{
        $conn = new PDO("mysql:host="._HOST.";dbname="._DB, _USER, _PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo "Error: " . $e->getMessage();
    }