<?php

    const _MODULE_DEFAULT = 'home';//Module default
    const _ACTION_DEFAULT = 'lists';//Action default

    const _INCODE = true; //Prevent direct access to files

    //Setup host
    define('_WEB_HOST_ROOT', 'http://'.$_SERVER['HTTP_HOST']
    . '/user_manager'); // home address

    define('_WEB_HOST_TEMPLATES', _WEB_HOST_ROOT . '/templates');
