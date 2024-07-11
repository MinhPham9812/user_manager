<?php
    if(!defined('_INCODE')) die('Access Denied...');

    $data = [
        'pageTitle' => 'Reset password'
    ];
    layout('header-login', $data);

    echo '<div class="container"><br>';
       // echo '<div class="text-center"><br>';

    //get token from URL
    $token = isset($_GET['token']) ? trim($_GET['token']) : '';
    // Check token from database
    $tokenQuery = firstRaw("SELECT id, email FROM users WHERE forgotToken = '$token'");
    $email = $tokenQuery['email'];

    //check token exist or not
    if(!empty($token)){
        //check token match with activeToken in database
        if(!empty($tokenQuery)){
            $userID = $tokenQuery['id'];
        
    

    if(isPOST()){
        $body = getBody(); //get all data from form

        $errors =[];

        //check password
        if(empty(trim($body['password']))){
            $errors['password']['required'] = 'Password is required.';
        }elseif(strlen(trim($body['password'])) < 8){
            $errors['password']['min'] = 'Password must be at least 8 characters.';
        }

        if(empty(trim($body['c_password']))){
            $errors['c_password']['required'] = 'Confirm Password is required.';
        }elseif((trim($body['c_password'])) != trim($body['password'])){
            $errors['c_password']['match'] = 'Password must be same.';
        }

        //check error
        if(empty($errors)){
            //update password
            $passwordHash = password_hash($body['password'],PASSWORD_DEFAULT);
            $dataUpdate = [
                'password' => $passwordHash,
                'forgotToken' => null,
                'updateAt' => date('Y-m-d H:i:s')
            ];

            $updateStatus = update('users', $dataUpdate, "id=$userID");
            if($updateStatus){
                setFlashData('msg','Updated password successfully. You can now log in.');
                setFlashData('msg_type', 'success');
                //send Email after reset password
                $subject = 'Your password has been reset';
                $content = 'Your password has been successfully reset. You can now log in with your new password.<br>';
                $content.= 'Thank you!<br>';
                sendMail($email, $subject, $content);
                redirect('?module=auth&action=login');
            }else{
                setFlashData('msg', 'System error, you cannot update password at this time.');
                setFlashData('msg_type', 'danger');
                redirect('?module=auth&action=reset&token='.$token);
            }
        }else{
            setFlashData('msg', 'Please check the data entered! ');
            setFlashData('msg_type', 'danger');
            setFlashData('errors', $errors);
            //setFlashData('oldData', $body);
            redirect('?module=auth&action=reset&token='.$token); //Reload the registration page
        }
    }
 
    $msg = getFlashData('msg');
    $msgType = getFlashData('msg_type');
    $errors = getFlashData('errors');
    //$oldData = getFlashData('oldData');
?>
    <div class="row"> 
       <div class="col-6" style="margin: 20px auto" >
            <h3 class="text-center text-uppercase">Reset Password</h3>
            <?php getMsg($msg, $msgType) ?>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="password" class="form-label text-start">Password</label>
                    <input id="password" 
                            class="form-control" 
                            type="password" name="password"
                            placeholder="Enter new password">
                    <?php
                        //show errors
                        echo form_error('password', $errors, '<span class="errors">', '</span>') ; 
                    ?>
                </div>
                

                <div class="mb-3">
                    <label for="c_password" class="form-label text-start">Confirm Password</label>
                    <input id="c_password" 
                            class="form-control" 
                            type="password" 
                            name="c_password" 
                            placeholder="Confirm your password">
                    <?php
                        //show errors
                        echo form_error('c_password', $errors, '<span class="errors">', '</span>') ; 
                    ?>
                </div>
                
                

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Reset password</button>
                </div>
                
                <hr>
                <p class="text-center"><a href="?module=auth&action=login">Login</a></p>
            </form>
        </div>
    </div>
<?php
        }else{
            getMsg('The link does not exist or has expired.', 'danger');
        }
    }else{
        getMsg('The link does not exist or has expired.', 'danger');
    }
       // echo "</div>";
    echo "</div>";
    layout('footer-login');
?>