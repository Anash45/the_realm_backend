<?php
include('functions.php');
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <?php include 'partials/_styles.php'; ?>
    </head>

    <body>
        <div class="container-scroller">
            <!-- partial:partials/_sidebar.html -->
            <?php include 'partials/_sidebar.php'; ?>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                <!-- partial:partials/_navbar.html -->
                <?php include 'partials/_navbar.php'; ?>
                <!-- partial -->
                <?php
                $info = '';
                if (isset($_REQUEST['submit'])) {
                    $username = sanitize($_REQUEST['username']);
                    $password = $_REQUEST['password'];
                    $confirmPassword = $_REQUEST['confirmPassword'];
                    $userRole = sanitize($_REQUEST['userRole']);
                    if (empty($username) || empty($password) || empty($confirmPassword) || empty($userRole)) {
                        $info = '<div class="alert alert-danger" role="alert">
                            All fields are required
                        </div>';
                    } else {
                        if ($password == $confirmPassword) {
                            $password = password_hash($password, PASSWORD_DEFAULT);
                            $insert = insertAccount($username, $password, $userRole);
                            if ($insert['success'] == true) {
                                $info = '<div class="alert alert-success" role="alert">
                                ' . $insert['data'] . '
                                </div>';
                            } else {
                                $info = '<div class="alert alert-danger" role="alert">
                                    ' . $insert['data'] . '
                                </div>';
                            }
                        } else {
                            $info = '<div class="alert alert-danger" role="alert">
                                        Passwords do not match!
                                    </div>';
                        }
                    }
                } else if (isset($_REQUEST['deleteAccount'])) {
                    $id = sanitize($_REQUEST['deleteAccount']);
                    $accountCheck = getAccountById($id);
                    if ($accountCheck['success'] == true && $accountCheck['data']['id'] !== 1) {
                        $delete = deleteAccountById($id);
                        if ($delete['success'] == true) {
                            $info = '<div class="alert alert-success" role="alert">
                            ' . $delete['data'] . '
                            </div>';
                        } else {
                            $info = '<div class="alert alert-danger" role="alert">
                                        ' . $delete['data'] . '
                                    </div>';
                        }
                    }
                }
                ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Accounts</h2>
                        </div>
                        <?php echo $info; ?>
                        <div class="row">
                            <div class="col-md-6 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Account</h4>
                                        <form class="forms-sample" method="POST">
                                            <div class="form-group">
                                                <label for="Username1">Username</label>
                                                <input type="text" class="form-control" name="username" id="Username1"
                                                    required placeholder="Username">
                                            </div>
                                            <div class="form-group">
                                                <label for="Password1">Password</label>
                                                <input type="password" class="form-control" name="password"
                                                    id="Password1" required placeholder="Password">
                                            </div>
                                            <div class="form-group">
                                                <label for="ConfirmPassword1">Confirm Password</label>
                                                <input type="password" class="form-control" name="confirmPassword"
                                                    id="ConfirmPassword1" required placeholder="Password">
                                            </div>
                                            <div class="form-group">
                                                <label for="userRole">Role</label>
                                                <select class="form-control" name="userRole" id="userRole">
                                                    <option value="" selected disabled>Select account type</option>
                                                    <option value="Admin">Admin</option>
                                                    <option value="User">User</option>
                                                </select>
                                            </div>
                                            <button class="btn btn-dark mr-2" type="reset">Cancel</button>
                                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 grid-margin stretch-card">
                                <?php
                                $allAccounts = getAllAccounts();
                                $accounts = $allAccounts['data'];
                                ?>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Accounts</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Username</th>
                                                        <th>Role</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (count($accounts) > 0) {
                                                        foreach ($accounts as $account) {
                                                            $actionBtns = '';
                                                            if ($account['id'] != 1) {
                                                                $actionBtns = '<a href="edit-account.php?id=' . $account['id'] . '" class="btn btn-dark mr-2">Edit</a>';
                                                                $actionBtns .= '<a href="?deleteAccount=' . $account['id'] . '" class="btn btn-danger deleteBtn">Delete</a>';
                                                            }


                                                            $userRole = ($account['role'] == 'User') ? 'badge-primary' : 'badge-success';
                                                            echo '<tr>
                                                            <td>' . $account['id'] . '</td>
                                                            <td>' . $account['username'] . '</td>
                                                            <td><label class="badge ' . $userRole . '">' . $account['role'] . '</label></td>
                                                            <td>' . $actionBtns . '</td>
                                                        </tr>';
                                                        }
                                                    } else {
                                                        echo '<div class="alert alert-danger">No accounts yet!</div>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <?php include 'partials/_footer.php'; ?>
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <?php include 'partials/_scripts.php'; ?>
    </body>

</html>