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
                // Check if form is submitted
                if (isset($_POST['submit'])) {
                    $id = $_GET['id']; // Assuming you have a hidden input field in your form to store the vote ID
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
                        // Call the updateVoteById function
                        $updateData = array(
                            'server_name' => $serverName,
                            'username' => $username,
                            'toplist' => $toplist,
                            'ip_address' => $ipAddress,
                            'reward_amount' => $rewardAmount,
                            'time_voted' => $timeVoted,
                            'claimed' => $claimed,
                            'vpn' => $vpn
                        );

                        $conditions = array('id' => $id);

                        $update = updateVote($updateData, $conditions);

                        if ($update['success']) {
                            $info = '<div class="alert alert-success" role="alert">' . $update['data'] . '</div>';
                        } else {
                            $info = '<div class="alert alert-danger" role="alert">' . $update['data'] . '</div>';
                        }
                    }
                }
                ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Votes</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mx-auto mb-3">
                                <a href="votes.php" class="btn btn-sm btn-primary"><i class="mdi mdi-arrow-left"></i>
                                    Back</a>
                            </div>
                        </div>
                        <?php if (isset($_REQUEST['id'])) {
                            $id = $_REQUEST['id'];
                            $vote = getVoteById($id);
                            if ($vote['success'] == true) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6 grid-margin stretch-card mx-auto">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Edit Vote</h4>
                                                <?php echo $info; ?>
                                                <form class="forms-sample" action="?id=<?php echo $id ?>" method="POST">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="serverName">Server Name</label>
                                                                <input type="text" class="form-control" name="serverName"
                                                                    id="serverName"
                                                                    value="<?php echo $vote['data']['server_name'] ?>" required
                                                                    placeholder="Server Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="username">Username</label>
                                                                <input type="text" class="form-control" name="username"
                                                                    id="username"
                                                                    value="<?php echo $vote['data']['username'] ?>" required
                                                                    placeholder="Username">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="toplist">Toplist</label>
                                                                <input type="text" class="form-control" name="toplist"
                                                                    id="toplist" value="<?php echo $vote['data']['toplist'] ?>"
                                                                    required placeholder="Toplist">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="ipAddress">IP Address</label>
                                                                <input type="text" class="form-control" name="ipAddress"
                                                                    id="ipAddress"
                                                                    value="<?php echo $vote['data']['ip_address'] ?>" required
                                                                    placeholder="IP Address">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="rewardAmount">Reward Amount</label>
                                                                <input type="number" class="form-control" name="rewardAmount"
                                                                    id="rewardAmount"
                                                                    value="<?php echo $vote['data']['reward_amount'] ?>"
                                                                    required placeholder="Reward Amount">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="timeVoted">Time Voted</label>
                                                                <input type="number" class="form-control" name="timeVoted"
                                                                    id="timeVoted"
                                                                    value="<?php echo $vote['data']['time_voted'] ?>" required
                                                                    placeholder="Time Voted">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="claimed">Claimed</label>
                                                                <input type="number" class="form-control" name="claimed"
                                                                    id="claimed" value="<?php echo $vote['data']['claimed'] ?>"
                                                                    required placeholder="Claimed">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="vpn">VPN</label>
                                                                <input type="number" class="form-control" name="vpn" id="vpn"
                                                                    value="<?php echo $vote['data']['vpn'] ?>" required
                                                                    placeholder="VPN">
                                                            </div>
                                                        </div>
                                                    </div>
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