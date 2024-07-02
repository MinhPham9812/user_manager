<?php
    if(!defined('_INCODE')) die('Access Denied...');

    $data = [
        'pageTitle' => 'Register'
    ];
    layout('header-login', $data);
?>
    <div class="row"> 
       <div class="col-6" style="margin: 20px auto" >
            <h3 class="text-center text-uppercase">Register</h3>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="fullname" class="form-label">First Name</label>
                    <input id="fullname" class="form-control" type="text" placeholder="Enter your first name">
                </div>

                <div class="mb-3">
                    <label for="fullname" class="form-label">Last Name</label>
                    <input id="fullname" class="form-control" type="text" placeholder="Enter your last name">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" placeholder="Enter your email">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" placeholder="Create a password">
                </div>

                <div class="mb-3">
                    <label for="c-password" class="form-label">Confirm Password</label>
                    <input id="c-password" class="form-control" type="password" placeholder="Confirm your password">
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