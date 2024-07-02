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
            $errors['password']['match'] = 'Password must be same.';
        }

        //Check errors
        if(empty($errors)){
            // no error
            setFlashData('msg', 'Validate successfully');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Please check the data entered! ');
            setFlashData('msg_type', 'danger');
        }
        echo "<pre>";
        print_r($errors);
        echo "</pre>";
    }
 
    $msg = getFlashData('msg');
    $msgType = getFlashData('msg_type');
?>
    <div class="row"> 
       <div class="col-6" style="margin: 20px auto" >
            <h3 class="text-center text-uppercase">Register</h3>
            
            <?php
                getMsg($msg, $msgType);
            ?>
            
            <form action="" method="post">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input id="firstname" class="form-control" name="firstname" type="text" placeholder="Enter your first name">
                </div>

                <div class="mb-3">
                    <label for="midlename" class="form-label">Midle Name</label>
                    <input id="midlename" class="form-control" name="midlename" type="text" placeholder="Enter your midle name">
                </div>

                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input id="lastname" class="form-control" name="lastname" type="text" placeholder="Enter your last name">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="Enter your email">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" name="password" type="password" placeholder="Create a password">
                </div>

                <div class="mb-3">
                    <label for="c-password" class="form-label">Confirm Password</label>
                    <input id="c-password" class="form-control" name="c_password" type="password" placeholder="Confirm your password">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </div>
                
                <hr>
                
                <p class="text-center"><a href="?module=auth&action=login">Sign In</a></p>
            </form>
        </div>
    </div>
<?php
    layout('footer-login');
?>