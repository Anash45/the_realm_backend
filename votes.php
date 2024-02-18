<?php
include('functions.php');
if (!isLoggedIn()) {
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
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Votes</h2>
                        </div>
                        <div class="row">
                            <?php
                            if (isAdmin()) {
                                $info = '';
                                if (isset($_POST['submit'])) {
                                    $serverName = sanitize($_POST['serverName']);
                                    $username = sanitize($_POST['username']);
                                    $toplist = sanitize($_POST['toplist']);
                                    $ipAddress = sanitize($_POST['ipAddress']);
                                    $rewardAmount = $_POST['rewardAmount'];
                                    $timeVoted = $_POST['timeVoted'];
                                    $claimed = $_POST['claimed'];
                                    $vpn = $_POST['vpn'];

                                    if (empty($serverName) || empty($username) || empty($toplist) || empty($ipAddress) || empty($rewardAmount) || empty($timeVoted) || empty($claimed) || empty($vpn)) {
                                        $info = '<div class="alert alert-danger" role="alert">All fields are required.</div>';
                                    } else {
                                        // Call the insertVote function
                                        $insert = insertVote($serverName, $username, $toplist, $ipAddress, $rewardAmount, $timeVoted, $claimed, $vpn);

                                        if ($insert['success'] === true) {
                                            $info = '<div class="alert alert-success" role="alert">' . $insert['data'] . '</div>';
                                        } else {
                                            $info = '<div class="alert alert-danger" role="alert">' . $insert['data'] . '</div>';
                                        }
                                    }
                                } else if (isset($_REQUEST['deleteVote'])) {
                                    $id = sanitize($_REQUEST['deleteVote']);
                                    $VoteCheck = getVoteById($id);
                                    if ($VoteCheck['success'] == true) {
                                        $delete = deleteVoteById($id);
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
                                <div class="col-md-12 grid-margin stretch-card">
                                    <div class="card">
                                        <?php echo $info; ?>
                                        <div class="card-body">
                                            <h4 class="card-title">Add Vote</h4>
                                            <form class="forms-sample" method="POST">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="serverName">Server Name</label>
                                                            <input type="text" class="form-control" name="serverName"
                                                                id="serverName" required placeholder="Server Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="username">Username</label>
                                                            <input type="text" class="form-control" name="username"
                                                                id="username" required placeholder="Username">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="toplist">Toplist</label>
                                                            <input type="text" class="form-control" name="toplist"
                                                                id="toplist" required placeholder="Toplist">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="ipAddress">IP Address</label>
                                                            <input type="text" class="form-control" name="ipAddress"
                                                                id="ipAddress" required placeholder="IP Address">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="rewardAmount">Reward Amount</label>
                                                            <input type="number" class="form-control" name="rewardAmount"
                                                                id="rewardAmount" required placeholder="Reward Amount">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="timeVoted">Time Voted</label>
                                                            <input type="number" class="form-control" name="timeVoted"
                                                                id="timeVoted" required placeholder="Time Voted">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="claimed">Claimed</label>
                                                            <input type="number" class="form-control" name="claimed"
                                                                id="claimed" required placeholder="Claimed">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="vpn">VPN</label>
                                                            <input type="number" class="form-control" name="vpn" id="vpn"
                                                                required placeholder="VPN">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-dark mr-2" type="reset">Cancel</button>
                                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <?php
                                $allVotes = getAllVotes();
                                $votes = $allVotes['data'];
                                // print_r($votes);
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
                                            <?php echo getPagination('finalized_votes'); ?>
                                        </div>
                                        <h4 class="card-title">Votes</h4>
                                        <div class="table-responsive">
                                            <table class="table dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Server Name</th>
                                                        <th>Username</th>
                                                        <th>Toplist</th>
                                                        <th>Reward Amount</th>
                                                        <th>Time Voted</th>
                                                        <th>Claimed</th>
                                                        <th>VPN</th>
                                                        <?php
                                                        if (isAdmin()) {
                                                            ?>
                                                            <th>IP Address</th>
                                                            <th>Action</th>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (count($votes) > 0) {
                                                        foreach ($votes as $vote) {
                                                            $adminTd = '';
                                                            if (isAdmin()) {
                                                                $actionBtns = '<a href="edit-vote.php?id=' . $vote['id'] . '" class="btn btn-dark mr-2">Edit</a>';
                                                                $actionBtns .= '<a href="?deleteVote=' . $vote['id'] . '" class="btn btn-danger deleteBtn">Delete</a>';
                                                                $adminTd = '<td>' . $vote['ip_address'] . '</td>
                                                                <td>' . $actionBtns . '</td>';
                                                            }

                                                            echo '<tr>
                                                        <td>' . $vote['id'] . '</td>
                                                        <td>' . $vote['server_name'] . '</td>
                                                        <td>' . $vote['username'] . '</td>
                                                        <td>' . $vote['toplist'] . '</td>
                                                        <td>' . $vote['reward_amount'] . '</td>
                                                        <td>' . $vote['time_voted'] . '</td>
                                                        <td>' . $vote['claimed'] . '</td>
                                                        <td>' . $vote['vpn'] . '</td>
                                                        ' . $adminTd . '
                                                    </tr>';
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="9" class="text-center">No votes available</td></tr>';
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