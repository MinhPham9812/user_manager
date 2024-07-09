<?php
if (!defined('_INCODE')) die('Access Denied...');

session_start(); // Đảm bảo session được khởi tạo

$data = [
    'pageTitle' => 'Forgot'
];
layout('header-login', $data);

//handle forgot password
if (isPOST()) {
    $body = getBody();

    //check email
    if (!empty(trim($body['email']))) {
        $email = trim($body['email']);
        
        //check email exist or not
        $queryUser = firstRaw("SELECT id FROM users WHERE email = '$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];
 
            //Check if user already has a token and it's still valid
            $tokenQuery = firstRaw("SELECT forgotToken FROM users WHERE id = '$userId'");
            $forgotToken = $tokenQuery['forgotToken'];

            if (empty($forgotToken)) {
                //Create forgot Token
                $forgotToken = sha1(uniqid() . time());
                $dataUpdate = [
                    'forgotToken' => $forgotToken
                ];
                $updateStatus = update('users', $dataUpdate, "id = $userId");

                if ($updateStatus) {
                    //Create link to reset password
                    $linkReset = _WEB_HOST_ROOT . '/?module=auth&action=reset&token=' . $forgotToken;
                    $subject = 'Password reset request';
                    $content = 'Please click the link below to reset your password: <br/>';
                    $content .= $linkReset . '<br>';
                    $content .= 'Thank you!';

                    //Proceed to send mail
                    $sendStatus = sendMail($email, $subject, $content);
                    if ($sendStatus) {
                        setFlashData('msg', 'Please check your email to reset your password.');
                        setFlashData('msg_type', 'success');
                    } else {
                        setFlashData('msg', 'The system is experiencing problems, please try again later.');
                        setFlashData('msg_type', 'danger');
                    }
                }
            } else {
                setFlashData('msg', 'An email has already been sent. Please check your email.');
                setFlashData('msg_type', 'warning');
            }
            header('Location: ?module=auth&action=forgot');
            exit;
        } else {
            setFlashData('msg', 'Email does not exist.');
            setFlashData('msg_type', 'danger');
            header('Location: ?module=auth&action=forgot');
            exit;
        }
    } else {
        setFlashData('msg', 'Email is required.');
        setFlashData('msg_type', 'danger');
        header('Location: ?module=auth&action=forgot');
        exit;
    }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-6" style="margin: 20px auto">
        <h3 class="text-center text-uppercase">Forgot password</h3>
        <?php getMsg($msg, $msgType) ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email"
                       class="form-control"
                       type="text" name="email"
                       placeholder="Enter your email"
                       value="<?php echo oldData('email', $oldData); //keep value correct ?>">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Forgot password</button>
            </div>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Login</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Sign Up</a></p>
        </form>
    </div>
</div>

<?php
layout('footer-login');
?>

