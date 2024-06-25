<?php

    const _MODULE_DEFAULT = 'home';//Module default
    const _ACTION_DEFAULT = 'lists';//Action default

    const _INCODE = true; //Prevent direct access to files

    //Setup host
    define('_WEB_HOST_ROOT', 'http://'.$_SERVER['HTTP_HOST']
    . '/user_manager'); // home address: http://localhost/user_manager

    define('_WEB_HOST_TEMPLATES', _WEB_HOST_ROOT . '/templates');

    //Setup path
    define('_WEB_PATH_ROOT', __DIR__); ///Applications/XAMPP/xamppfiles/htdocs/user_manager
    define('_WEB_PATH_TEMPLATES', _WEB_PATH_ROOT . '/templates');

    //Setup connect to Database
    const _HOST = 'localhost';
    const _USER = 'root';
    const _PASSWORD = '';
    const _DB = 'userManager';