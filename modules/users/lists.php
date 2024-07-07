<?php
    if(!defined('_INCODE')) die('Access Denied...');

    //check token login
    if(!isLogin()){
        redirect('?module=auth&action=login');
    }

    echo "this is lists.";