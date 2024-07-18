<?php
    if(!defined('_INCODE')) die('Access Denied...');

    $data = [
        'pageTitle' => 'Add user'
    ];
    layout('header', $data);

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


        //Check errors
        if(empty($errors)){
            // no error
            // setup information user
            $forgotToken = sha1(uniqid() . time());
            $dataInsert = [
                'email' => $body['email'],
                'firstname' => $body['firstname'],
                'middlename' => $body['middlename'],
                'lastname' => $body['lastname'],
                'forgotToken' => $forgotToken,
                'status' => 1,
                'createAt' => date('Y-m-d H:i:s') 
            ];
            //insert information user to database
            $insertStatus = insert('users', $dataInsert);
            if($insertStatus){
                
                if(empty($body['middlename'])){
                    $subject = $body['firstname'] . ' ' . $body['lastname'] . ' Please active your account';
                }else{
                    $subject = $body['firstname'] . ' ' . $body['middlename'] . ' ' .$body['lastname'] . ' Please active your account';
                }
                //Create link to update password
                $linkReset = _WEB_HOST_ROOT . '/?module=auth&action=reset&token=' . $forgotToken;
                $subject = 'Set your password';
                $content = 'Please click the link below to set your password:<br/>';
                $content .= '<a href="' . $linkReset . '">Set Password</a>';
                $sendStatus = sendMail($body['email'], $subject, $content);
                
                if($sendStatus){
                    setFlashData('msg', 'User added successfully.');
                    setFlashData('msg_type', 'success');
                    redirect('?module=auth&action=login');
                }else{
                    setFlashData('msg', 'There was an error adding the user.');
                    setFlashData('msg_type', 'danger');
                }
            }
        }else{
            setFlashData('msg', 'Please check the form for errors. ');
            setFlashData('msg_type', 'danger');
            setFlashData('errors', $errors);
            setFlashData('oldData', $body);
            redirect('?module=users&action=add'); //Reload the add page
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
            <h3 class="text-center text-uppercase">Add User</h3>
            
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
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
<?php
    layout('footer-login');
?>