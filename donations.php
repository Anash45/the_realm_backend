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
                            <h2 class="display1 text-center">Donations</h2>
                        </div>
                        <div class="row">
                            <?php
                            if (isAdmin()) {
                                ?>
                                <div class="col-md-12 grid-margin stretch-card">
                                    <?php
                                    $info = '';
                                    if (isset($_REQUEST['submit'])) {
                                        // Sanitize input
                                        $donorName = sanitize($_REQUEST['donor_name']);
                                        $amount = $_REQUEST['amount'];
                                        $ip_address = $_REQUEST['ip_address'];
                                        $date = $_REQUEST['date'];

                                        // Check if all fields are filled
                                        if (empty($donorName) || empty($amount) || empty($ip_address)) {
                                            $info = '<div class="alert alert-danger" role="alert">
                                                    All fields are required
                                                </div>';
                                        } else {
                                            if (empty($date)) {
                                                $date = date('Y-m-d');
                                            }
                                            // Insert the data
                                            $insert = insertDonation($donorName, $amount, $date, $ip_address);

                                            // Check if insertion was successful
                                            if ($insert['success'] == true) {
                                                $info = '<div class="alert alert-success" role="alert">
                                                        ' . $insert['data'] . ', refreshing in 2s!
                                                    </div>';
                                                echo '<script>setTimeout(function(){ window.location= "donations.php" }, 2000);</script>';
                                            } else {
                                                $info = '<div class="alert alert-danger" role="alert">
                                                        ' . $insert['data'] . '
                                                    </div>';
                                            }
                                        }
                                    } else if (isset($_REQUEST['deleteDonation'])) {
                                        $id = sanitize($_REQUEST['deleteDonation']);
                                        $DonationCheck = getDonationById($id);
                                        if ($DonationCheck['success'] == true) {
                                            $delete = deleteDonationById($id);
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
                                    <div class="card">
                                        <?php echo $info; ?>
                                        <div class="card-body">
                                            <h4 class="card-title">Add Donation</h4>
                                            <form class="forms-sample" method="POST">
                                                <div class="form-group">
                                                    <label for="DonorName">Donor Name</label>
                                                    <input type="text" class="form-control" name="donor_name" id="DonorName"
                                                        required placeholder="Donor Name">
                                                </div>
                                                <div class="form-group">
                                                    <label for="Amount">Amount</label>
                                                    <input type="number" class="form-control" name="amount" id="Amount"
                                                        required placeholder="Amount">
                                                </div>
                                                <div class="form-group">
                                                    <label for="Date">Date</label>
                                                    <input type="date" class="form-control" name="date" id="Date"
                                                        placeholder="Date">
                                                    <p><small>Leave empty if you want to use today's date!</small></p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="ipAddress">IP Address</label>
                                                    <input type="text" class="form-control" name="ip_address" id="ipAddress"
                                                        required placeholder="IP Address">
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
                            <div class="col-lg-12 grid-margin stretch-card">
                                <?php
                                $allDonations = getAllDonations();
                                $Donations = $allDonations['data'];
                                ?>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Donations</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Donor Name</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
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
                                                    if (count($Donations) > 0) {
                                                        foreach ($Donations as $donation) {

                                                            $adminTd = '';
                                                            if (isAdmin()) {
                                                                $actionBtns = '<a href="edit-donation.php?id=' . $donation['id'] . '" class="btn btn-dark mr-2">Edit</a>';
                                                                $actionBtns .= '<a href="?deleteDonation=' . $donation['id'] . '" class="btn btn-danger deleteBtn">Delete</a>';
                                                                $adminTd = '
                                                                <td>' . $donation['ip_address'] . '</td>
                                                                <td>' . $actionBtns . '</td>';
                                                            }

                                                            echo '<tr>
                                                            <td>' . $donation['id'] . '</td>
                                                            <td>' . $donation['donor_name'] . '</td>
                                                            <td>' . $donation['amount'] . '</td>
                                                            <td>' . date('d-m-Y', strtotime($donation['date'])) . '</td>
                                                            ' . $adminTd . '
                                                        </tr>';
                                                        }
                                                    } else {
                                                        echo '<div class="alert alert-danger">No donations yet!</div>';
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