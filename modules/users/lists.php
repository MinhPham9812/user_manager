<?php
    if(!defined('_INCODE')) die('Access Denied...');

    $data = [
        'pageTitle' => 'User Manager'
    ];
    layout('header', $data);
    
    //Get data from database
    $listUser = getRaw("SELECT * FROM users ORDER BY updateAt");
    
?>
    <div class="container">
        <hr>
        <h3>User Manager</h3>
        <p>
            <a href="#" class="btn btn-success btn-sm">Add user <i class="fa fa-plus"></i></a>
        </p>
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
                    <td><?php echo $count; ?></td>
                    <td><?php echo $item['middlename']?$item['firstname'] .' '. $item['middlename'] .' '. $item['lastname'] :$item['firstname'] .' '. $item['lastname'];  ?></td>
                    <td><?php echo $item['email']; ?></td>
                    <td><?php echo $item['status']==1?'<button type="button" class="btn btn-success btn-sm">Activated</button>'
                              : '<button type="button" class="btn btn-warning btn-sm">Not Activated</button>'; ?></td>
                    <td><a href="#" class="btn btn-warning btn-sm"><i class="fa-regular fa-pen-to-square"></i></a></td>
                    <td><a href="#" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
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
    </div>
<?php
    layout('footer');
?>