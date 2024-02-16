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
                    $storeName = $_POST['store_name'];
                    $logo = $_FILES['logo'];
                    $favicon = $_FILES['favicon'];
                    $color = $_POST['color'];

                    // Validate form data
                    if (empty($storeName) || empty($color)) {
                        $info = '<div class="alert alert-danger" role="alert">All fields are required.</div>';
                    } else {
                        // Upload logo file
                        $logoFileName = uploadFile($logo, 'logo');
                        if (!isFilename($logoFileName)) {
                            $info = '<div class="alert alert-danger" role="alert">' . $logoFileName . '</div>';
                        } else {
                            // Upload favicon file
                            $faviconFileName = uploadFile($favicon, 'favicon');
                            if (!isFilename($faviconFileName)) {
                                // Delete logo file if favicon upload fails
                                unlink("uploads/$logoFileName");
                                $info = '<div class="alert alert-danger" role="alert">' . $faviconFileName . '</div>';
                            } else {
                                // Insert data into database
                                $insert = insertStore($storeName, $logoFileName, $faviconFileName, $color);
                                if ($insert['success']) {
                                    $info = '<div class="alert alert-success" role="alert">' . $insert['data'] . '</div>';
                                } else {
                                    // Delete uploaded files if database insert fails
                                    unlink("uploads/$logoFileName");
                                    unlink("uploads/$faviconFileName");
                                    $info = '<div class="alert alert-danger" role="alert">' . $insert['data'] . '</div>';
                                }
                            }
                        }
                    }
                } else if (isset($_REQUEST['deleteStore'])) {
                    $id = sanitize($_REQUEST['deleteStore']);
                    $StoreCheck = getStoreById($id);
                    if ($StoreCheck['success'] == true) {
                        $faviconAddr = $StoreCheck['data']['favicon'];
                        $logoAddr = $StoreCheck['data']['logo'];
                        unlink("uploads/$logoAddr");
                        unlink("uploads/$faviconAddr");
                        $delete = deleteStoreById($id);
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
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Stores</h2>
                        </div>
                        <?php echo $info; ?>
                        <div class="row">
                            <div class="col-md-6 mx-auto grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Store</h4>
                                        <form class="forms-sample" method="POST" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="storeName">Store Name</label>
                                                <input type="text" class="form-control" name="store_name" id="storeName"
                                                    required placeholder="Enter store name">
                                            </div>
                                            <div class="form-group">
                                                <label for="logo">Logo</label>
                                                <input type="file" class="form-control" name="logo" id="logo" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="favicon">Favicon</label>
                                                <input type="file" class="form-control" name="favicon" id="favicon">
                                            </div>
                                            <div class="form-group">
                                                <label for="color">Color</label>
                                                <input type="color" class="form-control" name="color" id="color"
                                                    required>
                                            </div>
                                            <button class="btn btn-dark mr-2" type="reset">Cancel</button>
                                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php

                            // Retrieve all stores
                            $allStores = getAllStores();
                            if ($allStores['success']) {
                                $stores = $allStores['data'];
                            } else {
                                $stores = []; // Empty array if no stores found
                            }

                            ?>
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">All Stores</h4>
                                        <div class="table-responsive">
                                            <table class="table dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Store Name</th>
                                                        <th>Logo</th>
                                                        <th>Favicon</th>
                                                        <th>Color</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($stores as $store):
                                                        $actionBtns = '<a href="edit-store.php?id=' . $store['id'] . '" class="btn btn-dark mr-2">Edit</a>';
                                                        $actionBtns .= '<a href="?deleteStore=' . $store['id'] . '" class="btn btn-danger deleteBtn">Delete</a>';

                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $store['id']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $store['store_name']; ?>
                                                            </td>
                                                            <td><img class="store-logo"
                                                                    src="uploads/<?php echo $store['logo']; ?>" alt="Logo"
                                                                    width="50"></td>
                                                            <td><img class="store-logo"
                                                                    src="uploads/<?php echo $store['favicon']; ?>"
                                                                    alt="Favicon" width="50"></td>
                                                            <td>
                                                                <div class="color-ind"
                                                                    style="background-color: <?php echo $store['color']; ?>;">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <?php echo $actionBtns; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
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