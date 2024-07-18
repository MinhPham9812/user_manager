<?php
    if(!defined('_INCODE')) die('Access Denied...');

    $data = [
        'pageTitle' => 'Update User'
    ];
    layout('header', $data);

    // Get User id from URL
    $userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Check userId validate
        if($userId > 0){
            $userQuery = firstRaw("SELECT * FROM users WHERE id=$userId");

            if(!empty($userQuery)){
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
                    }
                }
                
                //Check errors
                if(empty($errors)){
                // no error
                // setup information user
                    $dataUpdate = [
                        'email' => $body['email'],
                        'firstname' => $body['firstname'],
                        'middlename' => $body['middlename'],
                        'lastname' => $body['lastname'],
                        'status' => $body['status'],
                        'updateAt' => date('Y-m-d H:i:s') 
                    ];
                    //insert information user to database
                    $updateStatus = update('users', $dataUpdate, "id=$userId");
                    if($updateStatus){
                        setFlashData('msg', 'User updated successfully.');
                        setFlashData('msg_type', 'success');
                        redirect('?module=users');
                    }else{
                        setFlashData('msg', 'There was an error updating the user.');
                        setFlashData('msg_type', 'danger');
                    }         
                }else{
                    setFlashData('msg', 'Please check the form for errors. ');
                    setFlashData('msg_type', 'danger');
                    setFlashData('errors', $errors);
                    setFlashData('oldData', $body);
                    redirect('?module=users&action=edit'); //Reload the add page
                }
            }
        }
    }
 
    $msg = getFlashData('msg');
    $msgType = getFlashData('msg_type');
    $errors = getFlashData('errors');
    $oldData = getFlashData('oldData');
    // echo "<pre>";
    // print_r($userQuery);
    // echo '</pre>';
?>
    <div class="row"> 
       <div class="col-6" style="margin: 20px auto" >
            <h3 class="text-center text-uppercase">Edit User</h3>
            
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
                            value="<?php echo $userQuery['firstname']; ?>">
                <?php
                    //show errors
                    echo form_error('firstname', $errors, '<span class="errors">', '</span>') ; 
                ?>
                </div>

                <div class="mb-3">
                    <label for="middlename" class="form-label">Middle Name</label>
                    <input id="middlename" 
                            class="form-control" 
                            name="middlename" 
                            type="text" 
                            placeholder="Enter your middle name"
                            value="<?php echo $userQuery['middlename']; ?>">
                </div>

                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input id="lastname" 
                            class="form-control" 
                            name="lastname" type="text" 
                            placeholder="Enter your last name"
                            value="<?php echo $userQuery['lastname']; ?>">
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
                            value="<?php echo $userQuery['email']; ?>">
                <?php
                    //show errors
                    echo form_error('email', $errors, '<span class="errors">', '</span>') ; 
                ?>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="0">Select status</option>
                        <option value="1" <?php echo (!empty($status) && $status==1)?'selected':false; ?>>Activated</option>
                        <option value="2" <?php echo (!empty($status) && $status==2)?'selected':false; ?>>Not Activated</option>
                    </select>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
<?php
    layout('footer-login');
?>