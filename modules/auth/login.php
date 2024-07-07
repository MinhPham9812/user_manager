<?php
    if(!defined('_INCODE')) die('Access Denied...');
    
    $data = [
        'pageTitle' => 'Login'
    ];
    layout('header-login', $data);

    //check status login
    if(isLogin()){
        redirect('?module=users');
    }

    //handle login
    if(isPOST()){
        $body = getBody();

        $errors = [];
        
        //check validate email
        if(empty(trim($body['email']))){
            $errors['email']['required'] = 'Email is required.';
        }else{
            if(!isEmail($body['email'])){
                $errors['email']['isEmail'] = 'Email Invalid';
            }else{
                // Check email already exist
                $email = trim($body['email']);
                $sql = "SELECT id FROM users WHERE email = '$email'";
                if(getRows($sql)==0){
                    $errors['email']['unique'] = 'Email address not exist.';
                }
            }
        }

        //check password
        if(empty(trim($body['password']))){
            $errors['password']['required'] = 'Password is required.';
        }else{
            //check password match or not in Database
            $password = $body['password'];
            //get password from database
            $userQuery = firstRaw("SELECT id, password FROM users WHERE email = '$email'");
            if(!empty($userQuery)){
                
                $passwordHash = $userQuery['password'];
                $userId = $userQuery['id'];
                if(password_verify($password, $passwordHash)){
                    //Create token login
                    $tokenLogin = sha1(uniqid().time());

                    //Insert data to the table: loginToken
                    $dataToken =[
                        'userID' => $userId,
                        'token' => $tokenLogin,
                        'createAt' => date('Y-m-d H:i:s')
                    ];

                    $insertTokenStatus = insert('loginToken', $dataToken);
                    if($insertTokenStatus){
                        // Save tokenLogin to the session
                        setSession('loginToken', $tokenLogin);
                        redirect('?module=users');
                    }else{
                        setFlashData('msg', 'System error, you cannot log in at this time.');
                        setFlashData('msg_type', 'danger');
                    }
                }else{
                    $errors['password']['match'] = 'Incorrect password';
                }
            }
        }

        // echo '<pre>';
        // print_r($errors); 
        // echo '</pre>';

        if(empty($errors)){
            setFlashData('msg', 'Logged in successfully');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Please check the data entered! ');
            setFlashData('msg_type', 'danger');
            setFlashData('errors', $errors);
            setFlashData('oldData', $body);
            redirect('?module=auth&action=login'); //Reload the login page
        }
        
    }

    $msg = getFlashData('msg');
    $msgType = getFlashData('msg_type');
    $errors = getFlashData('errors');
    $oldData = getFlashData('oldData');
    
?>
    <div class="row"> 
       <div class="col-6" style="margin: 20px auto" >
            <h3 class="text-center text-uppercase">Login</h3>
            <?php getMsg($msg, $msgType) ?>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" 
                            class="form-control" 
                            type="email" name="email" 
                            placeholder="Enter your email"
                            value="<?php echo oldData('email', $oldData); //keep value correct ?>">

                    <?php 
                        echo form_error('email', $errors, '<span class="errors">', '</span>'); 
                    ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" 
                            class="form-control" 
                            type="password" 
                            name="password" 
                            placeholder="Enter your password"
                            value="<?php echo oldData('password', $oldData); //keep value correct ?>">
                            
                            <?php 
                                echo form_error('password', $errors, '<span class ="errors">', '</span>'); 
                            ?>
                    </div>

                

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                
                <hr>
                <p class="text-center"><a href="?module=auth&action=forgot">Forgot password</a></p>
                <p class="text-center"><a href="?module=auth&action=register">Sign Up</a></p>
            </form>
        </div>
    </div>
    
<?php
    layout('footer-login');
?>