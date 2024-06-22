<?php
    if(!defined('_INCODE')) die('Access Denied...');

    function layout($layoutName){
        if(file_exists(_WEB_PATH_TEMPLATES. '/layout/'. $layoutName .'.php')){
            require_once _WEB_PATH_TEMPLATES. '/layout/'. $layoutName .'.php';
        }
    }