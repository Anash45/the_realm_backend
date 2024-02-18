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
                $info = $error = "";

                if (isset($_POST['submit'])) {
                    // Retrieve form data
                    $store = $_POST['store'];
                    $username = $_POST['username'];
                    $product = $_POST['product'];
                    $product_id = $_POST['product_id'];
                    $quantity = $_POST['quantity'];
                    $total_received = $_POST['total_received'];
                    $status = $_POST['status'];
                    $purchase_date = $_POST['purchase_date'];
                    $ip_address = $_POST['ip_address'];

                    // Validate form data (you may add more validation as needed)
                    if (empty($store) || empty($username) || empty($product) || empty($product_id) || empty($quantity) || empty($total_received) || empty($status) || empty($purchase_date) || empty($ip_address)) {
                        $error = '<div class="alert alert-danger" role="alert">All fields are required.</div>';
                    } else {
                        // Update donation information
                        $updateData = array(
                            'store' => $store,
                            'username' => $username,
                            'product' => $product,
                            'product_id' => $product_id,
                            'quantity' => $quantity,
                            'total_received' => $total_received,
                            'status' => $status,
                            'purchase_date' => $purchase_date,
                            'ip_address' => $ip_address
                        );

                        $conditions = array('id' => $_GET['id']); // Assuming you are passing the ID via GET parameter
                
                        // Update the donation
                        $updateResult = updateDonation($updateData, $conditions);
                        if ($updateResult['success']) {
                            $info = '<div class="alert alert-success" role="alert">Donation information updated successfully.</div>';
                        } else {
                            $error = '<div class="alert alert-danger" role="alert">Error updating donation information: ' . $updateResult['data'] . '</div>';
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
                            <div class="col-md-12 mx-auto mb-3">
                                <a href="donations.php" class="btn btn-sm btn-primary"><i
                                        class="mdi mdi-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <?php if (isset($_REQUEST['id'])) {
                            $id = $_REQUEST['id'];
                            $donation = getDonationById($id);
                            if ($donation['success'] == true) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12 grid-margin stretch-card mx-auto">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Edit Donation</h4>
                                                <?php echo $info; ?>
                                                <form class="forms-sample" method="POST">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="store">Store</label>
                                                                <input type="text" class="form-control" name="store" id="store"
                                                                    required placeholder="Store"
                                                                    value="<?php echo $donation['data']['store']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="username">Username</label>
                                                                <input type="text" class="form-control" name="username"
                                                                    id="username" required placeholder="Username"
                                                                    value="<?php echo $donation['data']['username']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="product">Product</label>
                                                                <input type="text" class="form-control" name="product"
                                                                    id="product" required placeholder="Product"
                                                                    value="<?php echo $donation['data']['product']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="product_id">Product ID</label>
                                                                <input type="number" class="form-control" name="product_id"
                                                                    id="product_id" required placeholder="Product ID"
                                                                    value="<?php echo $donation['data']['product_id']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="quantity">Quantity</label>
                                                                <input type="number" class="form-control" name="quantity"
                                                                    id="quantity" required placeholder="Quantity"
                                                                    value="<?php echo $donation['data']['quantity']; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="total_received">Total Received</label>
                                                                <input type="number" class="form-control" name="total_received"
                                                                    id="total_received" required placeholder="Total Received"
                                                                    value="<?php echo $donation['data']['total_received']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="status">Status</label>
                                                                <input type="text" class="form-control" name="status"
                                                                    id="status" required placeholder="Status"
                                                                    value="<?php echo $donation['data']['status']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="purchase_date">Purchase Date</label>
                                                                <input type="date" class="form-control" name="purchase_date"
                                                                    id="purchase_date" required placeholder="Purchase Date"
                                                                    value="<?php echo $donation['data']['purchase_date']; ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="ip_address">IP Address</label>
                                                                <input type="text" class="form-control" name="ip_address"
                                                                    id="ip_address" required placeholder="IP Address"
                                                                    value="<?php echo $donation['data']['ip_address']; ?>">
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