<?php
    if(!defined('_INCODE')) die('Access Denied...');

    $data = [
        'pageTitle' => 'Register'
    ];
    layout('header-login', $data);

    if(isPOST()){
        $body = getBody(); //get all data from form

        $errors = []; // array stores errors

        //Validate firstname and lastname: require, >= 3 characters
        if(empty(trim($body['firstname']))){
            $errors['firstname']['required'] = 'First name is required.';
        }else{
            if(strlen(trim($body['firstname'])) < 3){
                $errors['firstname']['min'] = 'First name must be at least 3 characters.';
            }
        }

        if(empty(trim($body['lastname']))){
            $errors['lastname']['required'] = 'Last name is required.';
        }else{
            if(strlen(trim($body['lastname'])) < 3){
                $errors['lastname']['min'] = 'Last name must be at least 3 characters.';
            }
        }

        //Validate email: required, email format, email must be unique
        if(empty(trim($body['email']))){
            $errors['email']['required'] = 'Email is required.';
        }else{
            if(!isEmail(trim($body['email']))){
                $errors['email']['isEmail'] = 'Invalid Email';
            }else{
                // Check email already exist
                $email = trim($body['email']);
                $sql = "SELECT id FROM users WHERE email = '$email'";
                if(getRows($sql)>0){
                    $errors['email']['unique'] = 'Email address already exist.';
                }
            }
        }

        //Validate password: required, at least 8 characters
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


        //Check errors
        if(empty($errors)){
            // no error
            // setup information user
            $activeToken = sha1(uniqid().time());
            $dataInsert = [
                'email' => $body['email'],
                'firstname' => $body['firstname'],
                'middlename' => $body['middlename'],
                'lastname' => $body['lastname'],
                'password' =>  password_hash($body['password'], PASSWORD_DEFAULT),
                'activeToken' => $activeToken,
                'createAt' => date('Y-m-d H:i:s') 
            ];
            //insert information user to database
            $insertStatus = insert('users', $dataInsert);
            if($insertStatus){
                //Create link to active account
                $linkActive = _WEB_HOST_ROOT.'/?module=auth&action=active&token='. $activeToken;
                if(empty($body['middlename'])){
                    $subject = $body['firstname'] . ' ' . $body['lastname'] . ' Please active your account';
                }else{
                    $subject = $body['firstname'] . ' ' . $body['middlename'] . ' ' .$body['lastname'] . ' Please active your account';
                }
                
                $content = 'Hello ' . $body['firstname'] . '<br/>';
                $content.= 'Please click link below to active your account: <br/>';
                $content.=$linkActive . '<br>/';
                $content.= 'Thank you!';

                //Proceed to send mail
                $sendStatus = sendMail($body['email'], $subject, $content);
                if($sendStatus){
                    setFlashData('msg', 'Successfully registered account. Please check your email to active account');
                    setFlashData('msg_type', 'success');
                    redirect('?module=auth&action=login');
                }else{
                    setFlashData('msg', 'The system is experiencing problems, please try again later');
                    setFlashData('msg_type', 'danger');
                }
            }

            

        }else{
            setFlashData('msg', 'Please check the data entered! ');
            setFlashData('msg_type', 'danger');
            setFlashData('errors', $errors);
            setFlashData('oldData', $body);
            redirect('?module=auth&action=register'); //Reload the registration page
        }
        
        
    }
 
    $msg = getFlashData('msg');
    $msgType = getFlashData('msg_type');
    $errors = getFlashData('errors');
    $oldData = getFlashData('oldData');
    // echo "<pre>";
    // print_r($oldData);
    // echo '</pre>';
?>
    <div class="row"> 
       <div class="col-6" style="margin: 20px auto" >
            <h3 class="text-center text-uppercase">Register</h3>
            
            <?php
                //show notice
                getMsg($msg, $msgType);
            ?>
            
            <form action="" method="post">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input id="firstname" 
                            class="form-control" 
                            name="firstname" type="text" 
                            placeholder="Enter your first name"
                            value="<?php echo oldData('firstname', $oldData); //keep value correct ?>">
                <?php
                    //show errors
                    echo form_error('firstname', $errors, '<span class="errors">', '</span>') ; 
                ?>
                </div>

                <div class="mb-3">
                    <label for="middlename" class="form-label">Middle Name</label>
                    <input id="middlename" 
                            class="form-control" 
                            name="midlename" 
                            type="text" 
                            placeholder="Enter your middle name">
                </div>

                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input id="lastname" 
                            class="form-control" 
                            name="lastname" type="text" 
                            placeholder="Enter your last name"
                            value="<?php echo oldData('lastname', $oldData); //keep value correct ?>">
                <?php
                    //show errors
                    echo form_error('lastname', $errors, '<span class="errors">', '</span>'); 
                ?>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" 
                            class="form-control" 
                            name="email" type="text" 
                            placeholder="Enter your email"
                            value="<?php echo oldData('email', $oldData); //keep value correct ?>">
                <?php
                    //show errors
                    echo form_error('email', $errors, '<span class="errors">', '</span>') ; 
                ?>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" 
                            class="form-control" 
                            name="password" 
                            type="password" 
                            placeholder="Create a password"
                            value="<?php echo oldData('password', $oldData); //keep value correct ?>">
                <?php
                    //show errors
                    echo form_error('password', $errors, '<span class="errors">', '</span>') ; 
                ?>
                </div>

                <div class="mb-3">
                    <label for="c_password" class="form-label">Confirm Password</label>
                    <input id="c_password" 
                            class="form-control" 
                            name="c_password" 
                            type="password" 
                            placeholder="Confirm your password"
                            value="<?php echo oldData('c_password', $oldData); //keep value correct ?>">
                <?php
                    //show errors
                    echo form_error('c_password', $errors, '<span class="errors">', '</span>') ; 
                ?>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </div>
                
                <hr>
                
                <p class="text-center"><a href="?module=auth&action=login">Login</a></p>
            </form>
        </div>
    </div>
<?php
    layout('footer-login');
?>