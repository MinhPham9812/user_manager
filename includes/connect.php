<?php
    if(!defined('_INCODE')) die('Access Denied...');

    require_once __DIR__ . '/../config.php'; 
    try{
        $conn = new PDO("mysql:host="._HOST.";dbname="._DB, _USER, _PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        require_once 'modules/errors/database.php';
        die();
    }