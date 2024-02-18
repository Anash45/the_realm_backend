<?php
include('functions.php');
if (!isLoggedIn() || !isAdmin()) {
    header("Location: login.php");
}
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
                if (isset($_REQUEST['kickSession'])) {
                    $id = sanitize($_REQUEST['kickSession']);
                    $delete = deleteSessionById($id);
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
                ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Sessions</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mx-auto">
                                <?php echo $info; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 grid-margin stretch-card mx-auto">
                                <?php
                                $allSessions = getAllActiveSessions();
                                $sessions = $allSessions['data'];
                                ?>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="py-3 d-flex flex-column w-100 gap-3">
                                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                                                <div class="form-group mb-0">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>"
                                                            placeholder="Search..." aria-label="Search..."
                                                            aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-sm btn-primary"
                                                                type="submit">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php echo getPagination('online_users'); ?>
                                        </div>
                                        <h4 class="card-title">Active Sessions</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Username</th>
                                                        <th>IP Address</th>
                                                        <th>Last Login</th>
                                                        <th>Kick</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (count($sessions) > 0) {
                                                        foreach ($sessions as $session) {

                                                            $actionBtns = '<a href="?kickSession=' . $session['id'] . '" class="btn btn-danger deleteBtn mr-2">Delete</a>';

                                                            echo '<tr>
                                                            <td>' . $session['id'] . '</td>
                                                            <td>' . $session['username'] . '</td>
                                                            <td>' . $session['ip_address'] . '</td>
                                                            <td>' . date('h:i:s a | d-M-Y', strtotime($session['last_activity'])) . '</td>
                                                            <td>' . $actionBtns . '</td>
                                                        </tr>';
                                                        }
                                                    } else {
                                                        echo '<div class="alert alert-danger">No sessions yet!</div>';
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