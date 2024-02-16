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
                                // print_r($_REQUEST);
                                if (isset($_POST['submit'])) {
                                    // Retrieve form data
                                    $username = sanitize($_POST['username']);
                                    $password = $_POST['password'];

                                    // You should validate/sanitize the input here
                                
                                    // Check user credentials against database
                                    $loginResult = getAccountByUsername($username);

                                    if ($loginResult['success']) {
                                        if (password_verify($password, $loginResult['data']['password'])) {
                                            // Authentication successful
                                            $user = $loginResult['data'];

                                            // Store user data in session variables
                                            $_SESSION['user_id'] = $user['id'];
                                            $_SESSION['username'] = $user['username'];
                                            $_SESSION['role'] = $user['role'];
                                            
                                            updateOnlineStatus();
                                            // Redirect to the dashboard or another authorized page
                                            header("Location: index.php");
                                            exit();
                                        } else {
                                            // Authentication failed
                                            $info = '<div class="alert alert-danger" role="alert">Invalid password.</div>';
                                        }
                                    } else {
                                        // Authentication failed
                                        $info = '<div class="alert alert-danger" role="alert">Invalid username.</div>';
                                    }
                                } else if (isset($_REQUEST['loginErr'])) {
                                    $info = '<div class="alert alert-danger" role="alert">You need to login first!</div>';
                                }
                                ?>
                                <h3 class="card-title text-left mb-3">Login</h3>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <?php echo $info; ?>
                                    <div class="form-group">
                                        <label>Username or email *</label>
                                        <input type="text" class="form-control p_input" name="username">
                                    </div>
                                    <div class="form-group">
                                        <label>Password *</label>
                                        <input type="password" class="form-control p_input" name="password">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block enter-btn" name="submit">Login</button>
                                    </div>
                                    <p class="sign-up">Don't have an Account?<a href="signup.php"> Sign Up</a></p>
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