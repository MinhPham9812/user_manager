<?php
    if(!defined('_INCODE')) die('Access Denied...');
    if(isLogin()){
        $token = getSession('loginToken');
        delete('loginToken', "token=$token");
        removeSession('loginToken');
        redirect('?module=auth&action=login');
    }