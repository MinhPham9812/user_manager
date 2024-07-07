<?php
    if(!defined('_INCODE')) die('Access Denied...');
    layout('header-login');

    echo '<div class="container text-center"><br>'; 
    $token = getBody()['token'];
    // Check token from database
    $tokenQuery = firstRaw("SELECT id FROM users WHERE activeToken = '$token'");
    //print_r($tokenQuery);
    //echo $tokenQuery['id'];

    //check token exist or not
    if(!empty($token)){
        //check token match with activeToken in database
        if(!empty($tokenQuery)){
            $userID = $tokenQuery['id'];
            $updateData = [
                'status' => 1,
                'activeToken' => null
            ];
            $updateStatus = update('users', $updateData, "id=$userID");
            if($updateStatus){
                setFlashData('msg', 'Account activation successful!');
                setFlashData('msg_type', 'success');
            }else{
                setFlashData('msg', 'Account activation failed!');
                setFlashData('msg_type', 'danger');
            }
        
        redirect('?module=auth&action=login');
            
        }else{
            getMsg('The link does not exist or has expired.', 'danger');
        }
    }else{
        getMsg('The link does not exist or has expired.', 'danger');
    }

    echo "</div>";
    layout('footer-login');