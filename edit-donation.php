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
                    // Sanitize input
                    $donorName = sanitize($_POST['donor_name']);
                    $amount = $_POST['amount'];
                    $date = $_POST['date'];
                    $ip_address = $_POST['ip_address'];
                    $donationId = $_GET['id']; // Assuming the ID is passed in the URL parameter
                
                    // Check if all fields are filled
                    if (empty($donorName) || empty($amount) || empty($date) || empty($ip_address)) {
                        $info = '<div class="alert alert-danger" role="alert">
                                    All fields are required!
                                </div>';
                    } else {
                        // Prepare update data
                        $updateData = array(
                            'donor_name' => sanitize($donorName),
                            'amount' => sanitize($amount),
                            'date' => sanitize($date),
                            'ip_address' => sanitize($ip_address)
                        );

                        // Prepare condition
                        $conditions = array(
                            'id' => $donationId
                        );

                        // Call the updateDonation function
                        $updateResult = updateDonation($updateData, $conditions);

                        // Check the result and set the message accordingly
                        if ($updateResult['success'] === true) {
                            $info = '<div class="alert alert-success" role="alert">
                                        Donation updated successfully.
                                    </div>';
                        } else {
                            $info = '<div class="alert alert-danger" role="alert">
                                        ' . $updateResult['data'] . '
                                    </div>';
                        }
                    }
                }
                ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Donations</h2>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mx-auto mb-3">
                                <a href="donations.php" class="btn btn-sm btn-primary"><i class="mdi mdi-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <?php if (isset($_REQUEST['id'])) {
                            $id = $_REQUEST['id'];
                            $Donation = getDonationById($id);
                            if ($Donation['success'] == true) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6 grid-margin stretch-card mx-auto">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Edit Donation</h4>
                                                <?php echo $info; ?>
                                                <form class="forms-sample" method="POST">
                                                    <div class="form-group">
                                                        <label for="DonorName">Donor Name</label>
                                                        <input type="text" class="form-control" name="donor_name" id="DonorName"
                                                            required value="<?php echo $Donation['data']['donor_name']; ?>"
                                                            placeholder="Donor Name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="amount">Amount</label>
                                                        <input type="number" class="form-control" name="amount" id="amount"
                                                            required placeholder="Amount"
                                                            value="<?php echo $Donation['data']['amount']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Date">Date</label>
                                                        <input type="date" class="form-control" name="date" id="Date"
                                                            placeholder="Date" value="<?php echo $Donation['data']['date']; ?>">
                                                        <p><small>Leave empty if you want to use today's date!</small></p>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ipAddress">IP Address</label>
                                                        <input type="text" class="form-control" name="ip_address" id="ipAddress"
                                                            required placeholder="IP Address"
                                                            value="<?php echo $Donation['data']['ip_address']; ?>">
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