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
                            <div class="col-lg-6 grid-margin stretch-card mx-auto">
                                <?php
                                $allSessions = getAllActiveSessions();
                                $sessions = $allSessions['data'];
                                ?>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Active Sessions</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Username</th>
                                                        <th>Last Activity</th>
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
                                                            <td>' . date('d-m-Y H:i:s', strtotime($session['last_activity'])) . '</td>
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