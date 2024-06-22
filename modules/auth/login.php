<?php
    if(!defined('_INCODE')) die('Access Denied...');
    
    $data = [
        'pageTitle' => 'Login'
    ];
    layout('header-login', $data);
?>
    <div class="row"> 
       <div class="col-6" style="margin: 20px auto" >
            <h3 class="text-center text-uppercase">Login</h3>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" placeholder="abc@example.com">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control">
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