<?php
    if(!defined('_INCODE')) die('Access Denied...');

    
    $body = getBody();
    if(!empty($body['id'])){
        // Get User id from URL
        $userId = $body['id'];
        $userQuery = getRows("SELECT id FROM users WHERE id=$userId");
        if($userId > 0){
            //1. Delete login_token
            $deleteToken = delete('loginToken',"userID=$userId");
            if($deleteToken){
                $deleteUser = delete('users', "id=$userId");
                if($deleteUser){
                    setFlashData('msg', 'User deleted successfully.' );
                    setFlashData('msg_type', 'success');
                }else{
                    setFlashData('msg', 'There was an error deleting the user.');
                    setFlashData('msg_type', 'danger');
                }
            }
        }else{
            setFlashData('msg', 'User does not exist.' );
            setFlashData('msg_type', 'danger');
        }
    }else{
        setFlashData('msg', 'The link not exist.' );
        setFlashData('msg_type', 'danger');
    }

    redirect('?module=users');