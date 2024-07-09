<?php
    if(!defined('_INCODE')) die('Access Denied...');

    $data = [
        'pageTitle' => 'Reset password'
    ];
    layout('header-login', $data);

    echo '<div class="container text-center"><br/>';

    $token = getBody()['token'];
    // Check token from database
    $tokenQuery = firstRaw("SELECT id FROM users WHERE forgotToken = '$token'");
    

    //check token exist or not
    if(!empty($token)){
        //check token match with activeToken in database
        if(!empty($tokenQuery)){
            $userID = $tokenQuery['id'];
        
    

    // if(isPOST()){
    //     $body = getBody(); //get all data from form

    //     //check password
    //     if(!empty(trim($body['password']))){
    //         $password = $body['password'];
    //         if(strlen(trim($body['password'])) < 8){
    //             setFlashData('msg', 'Password must be at least 8 character.');
    //             setFlashData('msg_type', 'danger');
    //         }else{

    //         }
    //     }else{
    //         setFlashData('msg', 'Password is requerid.');
    //         setFlashData('msg_type', 'danger');
    //     }

    //     //check c_password
    //     if(!empty(trim($body['c_password']))){
    //         if(trim($body['c_password']) != trim($body['password'])){
    //             setFlashData('msg', 'Password must be same.');
    //             setFlashData('msg_type', 'danger');
    //         }
    //     }else{
    //         setFlashData('msg', 'Confirm password is requerid.');
    //         setFlashData('msg_type', 'danger');
    //     }
    // }
 
    // $msg = getFlashData('msg');
    // $msgType = getFlashData('msg_type');
    // $errors = getFlashData('errors');
    // $oldData = getFlashData('oldData');
?>
    <div class="row"> 
       <div class="col-6" style="margin: 20px auto" >
            <h3 class="text-center text-uppercase">Reset password</h3>
            
            <?php
                //show notice
                getMsg($msg, $msgType);
            ?>
            
            <form action="" method="post">
               

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" 
                            class="form-control" 
                            name="password" 
                            type="password" >
                </div>

                <div class="mb-3">
                    <label for="c-password" class="form-label">Confirm Password</label>
                    <input id="c-password" 
                            class="form-control" 
                            name="c_password" 
                            type="password">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Reset password</button>
                </div>
                
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

    echo "</div>";
    layout('footer-login');
?>