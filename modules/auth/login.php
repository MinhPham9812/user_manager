<?php
    if(!defined('_INCODE')) die('Access Denied...');
    
    require_once _WEB_PATH_TEMPLATES. '/layout/header.php';
?>
    <div class="row"> 
        <div class="col-6" style="margin: 20px auto" >
            <h3 class="text-center">Login</h3>
            <form action="" method="post">
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" class="form-control" placeholder="abc@example.com">
                </div>

                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" class="form-control">
                </div>

                <button style="margin: 10px auto" type="submit" class="btn btn-primary btn-block">Enter</button>
            </form>
        </div>
    </div>
<?php
    require_once _WEB_PATH_TEMPLATES. '/layout/footer.php';
?>