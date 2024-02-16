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
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="row w-100 m-0">
                    <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
                        <div class="card col-lg-4 mx-auto">
                            <div class="card-body px-5 py-5">
                                <?php
                                $info = '';
                                if (isset($_POST['submit'])) {
                                    $username_email = sanitize($_POST['username']);
                                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                    $userRole = 'User';
                                
                                    // Perform validation and sanitization as needed
                                
                                    // Insert account into the database
                                    $insertResult = insertAccount($username_email, $password, $userRole);

                                    if ($insertResult['success']) {
                                        // Account created successfully
                                        $info = '<div class="alert alert-success" role="alert">Account created successfully.</div>';
                                    } else {
                                        // Account creation failed
                                        $info = '<div class="alert alert-danger" role="alert">Error: ' . $insertResult['data'] . '</div>';
                                    }
                                }
                                ?>
                                <h3 class="card-title text-left mb-3">Signup</h3>
                                <?php  echo $info;?>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);  ?>" autocomplete="off" class="text-left">
                                    <div class="form-group">
                                        <label>Username or email *</label>
                                        <input type="text" class="form-control p_input" required  name="username">
                                    </div>
                                    <div class="form-group">
                                        <label>Password *</label>
                                        <input type="password" class="form-control p_input" required  name="password">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block enter-btn" name="submit">Signup</button>
                                    </div>
                                    <p class="sign-up">Already a member?<a href="login.php"> Login</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->
                </div>
                <!-- row ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <?php include 'partials/_scripts.php'; ?>
    </body>

</html>