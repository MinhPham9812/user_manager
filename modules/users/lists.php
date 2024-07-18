<?php
    if(!defined('_INCODE')) die('Access Denied...');

    $data = [
        'pageTitle' => 'User Manager'
    ];
    layout('header', $data);
    //Data filtering processing
    $filter = '';
    if(isGET()){
        $body = getBody();

        //filter status (option has value = 0, so $status = empty)
        if(!empty($body['status'])){
            $status = $body['status'];

            //check status
            if($status == 2){
                $statusSQL = 0;
            }else{
                $statusSQL = $status;
            }


            if(!empty($filter) && strpos($filter, 'WHERE') >=0){
                $operator = 'AND';
            }else{
                $operator = 'WHERE';
            }

            $filter.= "WHERE status=$statusSQL";
        }

        //filter keywords
        if(!empty($body['keywords'])){
            $keyword = $body['keywords'];

            if(!empty($filter) && strpos($filter, 'WHERE') >=0){
                $operator = 'AND';
            }else{
                $operator = 'WHERE';
            }

            $filter.= " $operator (firstname LIKE '%$keyword%' OR lastname LIKE '%$keyword%' OR middlename LIKE '%$keyword%') ";
        }
    }

    //Handle pagination
    //Count user
    $allUserNum = getRows("SELECT * FROM users $filter");
    
    //1.Identify records on a page
    $perPage = 3; //Each page has 3 records

    //2.Calculate the number of pages
    $maxPage = ceil($allUserNum/$perPage);

    //3.Handle the page number based on the get method
    $page = getBody()['page'];
    if(!empty($page)){
        //check if page wrong
        if($page<1 || $page>$maxPage){
            $page = 1;
        }
    }else{
        $page = 1;
    }

    //4. Calculate offset in limit based on $page
    /*
        $page = 1 => offset = 0
        $page = 2 => offset = 3
        $page = 3 => offset = 6
        => offset = ($page-1)*3
    */
    $offset = ($page-1)*3;

    //Get data from database
    $listUser = getRaw("SELECT * FROM users $filter ORDER BY createAt LIMIT $offset, $perPage");
    

    //Handles search query strings with pagination
    $queryString = null;
    if(!empty($_SERVER['QUERY_STRING'])){
        $queryString = $_SERVER['QUERY_STRING'];
        $queryString = str_replace('module=users', '', $queryString);
        $queryString = str_replace('&page='.$page, '', $queryString);
        $queryString = trim($queryString, '&');
        $queryString = '&'.$queryString;
    }
?>
    <div class="container">
        <hr>
        <h3>User Manager</h3>
        <p>
            <a href="?module=users&action=add" class="btn btn-success btn-sm">Add user <i class="fa fa-plus"></i></a>
        </p>
        <form action="" method="get">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="0">Select status</option>
                            <option value="1" <?php echo (!empty($status) && $status==1)?'selected':false; ?>>Activated</option>
                            <option value="2" <?php echo (!empty($status) && $status==2)?'selected':false; ?>>Not Activated</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <input type="search" class="form-control" name="keywords" placeholder="search keywords..." 
                    value="<?php echo (!empty($keyword))?$keyword:false; ?>">
                </div>    
                <div class="col-3 d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
            <input type="hidden" name="module" value="users">
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th width="5%">Edit</th>
                    <th width="5%">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(!empty($listUser)):
                        $count = 0; // number of order
                        foreach($listUser as $item):
                            $count++;
                ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['middlename']?$item['firstname'] .' '. $item['middlename'] .' '. $item['lastname'] :$item['firstname'] .' '. $item['lastname'];  ?></td>
                    <td><?php echo $item['email']; ?></td>
                    <td><?php echo $item['status']==1?'<button type="button" class="btn btn-success btn-sm">Activated</button>'
                              : '<button type="button" class="btn btn-warning btn-sm">Not Activated</button>'; ?></td>
                    <td><a href="?module=users&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fa-regular fa-pen-to-square"></i></a></td>
                    <td><a href="?module=users&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="7">
                        <div class="alert alert-danger text-center">No users</div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <?php
                    $prevPage = $page - 1;
                    if($page > 1){
                       echo  '<li class="page-item"><a class="page-link" href=" '. _WEB_HOST_ROOT.'?module=users'.$queryString.'&page='.$prevPage .'">Previous</a></li>';
                    }
                    
                ?>
                
                <?php 
                $begin = $page - 2;
                if($begin < 1){
                    $begin = 1;
                }
                $end = $page + 2;
                if($end > $maxPage){
                    $end = $maxPage;
                }
                for($i=$begin; $i<=$end; $i++){ ?>
                <li class="page-item <?php echo ($i==$page)?'active':false; ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT.'?module=users'.$queryString.'&page='.$i; ?>"><?php echo $i; ?></a></li>
                <?php }; ?>    
                <?php
                    $nextPage = $page + 1;
                    if($page < $maxPage){
                        echo '<li class="page-item"><a class="page-link" href="'. _WEB_HOST_ROOT.'?module=users'.$queryString.'&page='.$nextPage .'">Next</a></li>';
                    }
                ?>
                
            </ul>
        </nav>
    </div>
    <hr>
<?php
    layout('footer');
?>