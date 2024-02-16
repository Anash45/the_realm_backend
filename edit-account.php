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
                // Check if form is submitted
                if (isset($_POST['submit'])) {
                    // Get form data
                    $id = $_GET['id']; // Assuming the ID is passed in the URL parameter
                    $newUsername = $_POST['username'];
                    $newRole = $_POST['userRole'];

                    // Sanitize input data
                    $id = sanitize($id);
                    $newUsername = sanitize($newUsername);
                    $newRole = sanitize($newRole);

                    // Check if the new username already exists for another account
                    $existingAccount = getAccountByUsername($newUsername);
                    if ($existingAccount['success'] == true && $existingAccount['data']['id'] != $id) {
                        $info = '<div class="alert alert-danger" role="alert">
                    Username already exists for another account.
                </div>';
                    } else {
                        // Prepare update data
                        $updateData = array(
                            'username' => $newUsername,
                            'role' => $newRole
                        );

                        // Prepare condition
                        $conditions = array(
                            'id' => $id
                        );

                        // Call the updateAccount function
                        $updateResult = updateAccount($updateData, $conditions);

                        // Check the result and set the message accordingly
                        if ($updateResult['success'] === true) {
                            $info = '<div class="alert alert-success" role="alert">
                        Account updated successfully.
                    </div>';
                        } else {
                            $info = '<div class="alert alert-danger" role="alert">
                        Error updating account: ' . $updateResult . '
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
                        <div class="row">
                            <div class="col-lg-6 mx-auto">
                                <a href="accounts.php" class="btn btn-sm btn-primary"><i class="mdi mdi-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <?php if (isset($_REQUEST['id'])) {
                            $id = $_REQUEST['id'];
                            $account = getAccountById($id);
                            if ($account['success'] == true) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6 grid-margin stretch-card mx-auto">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Edit Account</h4>
                                                <?php echo $info; ?>
                                                <form class="forms-sample" action="?id=<?php echo $id ?>" method="POST">
                                                    <div class="form-group">
                                                        <label for="Username1">Username</label>
                                                        <input type="text" class="form-control" name="username" id="Username1"
                                                            required value="<?php echo $account['data']['username']; ?>"
                                                            placeholder="Username">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="userRole">Role</label>
                                                        <select class="form-control" name="userRole" id="userRole">
                                                            <option value="" selected disabled>Select account type</option>
                                                            <option value="Admin">Admin</option>
                                                            <option value="User">User</option>
                                                        </select>
                                                    </div>
                                                    <script>
                                                        document.getElementById('userRole').value = "<?php echo $account['data']['role']; ?>";
                                                    </script>
                                                    <button class="btn btn-dark mr-2" type="reset">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } ?>
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