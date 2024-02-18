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
                                    if (isset($_POST['submit'])) {
                                        // Retrieve form data and sanitize
                                        $store = htmlspecialchars($_POST['store']);
                                        $username = htmlspecialchars($_POST['username']);
                                        $product = htmlspecialchars($_POST['product']);
                                        $product_id = $_POST['product_id'];
                                        $quantity = $_POST['quantity'];
                                        $total_received = $_POST['total_received'];
                                        $status = htmlspecialchars($_POST['status']);
                                        $purchase_date = $_POST['purchase_date'];
                                        $ip_address = $_POST['ip_address'];

                                        // Validate and sanitize the rest of the form fields as needed...
                                
                                        // Call the insertDonation function
                                        $result = insertDonation($store, $username, $product, $product_id, $quantity, $total_received, $status, $purchase_date, $ip_address);

                                        // Check the result of the insertion
                                        if ($result['success']) {
                                            // Display success message
                                            $info = '<div class="alert alert-success" role="alert">' . $result['data'] . '</div>';
                                        } else {
                                            // Display error message
                                            $error = '<div class="alert alert-danger" role="alert">' . $result['data'] . '</div>';
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
                                        <div class="card-body">
                                            <?php echo $info; ?>
                                            <h4 class="card-title">Add Donation</h4>
                                            <form class="forms-sample" method="POST">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="store">Store</label>
                                                            <input type="text" class="form-control" name="store" id="store"
                                                                required placeholder="Store">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="username">Username</label>
                                                            <input type="text" class="form-control" name="username"
                                                                id="username" required placeholder="Username">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="product">Product</label>
                                                            <input type="text" class="form-control" name="product"
                                                                id="product" required placeholder="Product">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="product_id">Product ID</label>
                                                            <input type="number" class="form-control" name="product_id"
                                                                id="product_id" required placeholder="Product ID">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="number">Quantity</label>
                                                            <input type="number" class="form-control" name="quantity"
                                                                id="quantity" required placeholder="Quantity">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="total_received">Total Received</label>
                                                            <input type="number" class="form-control" name="total_received"
                                                                id="total_received" required placeholder="Total Received">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <input type="text" class="form-control" name="status"
                                                                id="status" required placeholder="Status">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="purchase_date">Purchase Date</label>
                                                            <input type="date" class="form-control" name="purchase_date"
                                                                id="purchase_date" required placeholder="Purchase Date">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="ip_address">IP Address</label>
                                                            <input type="text" class="form-control" name="ip_address"
                                                                id="ip_address" required placeholder="IP Address">
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
                            <div class="col-lg-12 grid-margin stretch-card">
                                <?php
                                $allDonations = getAllDonations();
                                $Donations = $allDonations['data'];
                                ?>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="py-3 d-flex flex-column w-100 gap-3">
                                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                                                <div class="form-group mb-0">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="q" value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>"
                                                            placeholder="Search..."
                                                            aria-label="Search..."
                                                            aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-sm btn-primary"
                                                                type="submit">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php echo getPagination('store_payments'); ?>
                                        </div>
                                        <h4 class="card-title">Donations</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Store</th>
                                                        <th>Username</th>
                                                        <th>Product</th>
                                                        <th>Product ID</th>
                                                        <th>Quantity</th>
                                                        <th>Status</th>
                                                        <th>Amount</th>
                                                        <th>Purchase Date</th>
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
                                                            <td>' . $donation['store'] . '</td>
                                                            <td>' . $donation['username'] . '</td>
                                                            <td>' . $donation['product'] . '</td>
                                                            <td>' . $donation['product_id'] . '</td>
                                                            <td>' . $donation['quantity'] . '</td>
                                                            <td>' . $donation['status'] . '</td>
                                                            <td>' . $donation['total_received'] . '</td>
                                                            <td>' . date('d-m-Y', $donation['unix_time']) . '</td>
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