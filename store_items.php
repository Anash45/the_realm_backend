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
                    $store = $_POST['store'];
                    $itemName = $_POST['item_name'];
                    $itemAmount = $_POST['item_amount'];
                    $itemID = $_POST['item_id'];
                    $itemDescription = $_POST['item_description'];
                    $defaultPrice = $_POST['default_price'];
                    $salePrice = $_POST['sale_price'];
                    $category = $_POST['category'];
                    $amountPurchased = $_POST['amount_purchased'];
                    $hotDeal = $_POST['hot_deal'];

                    // Upload logo file
                    $imageFileName = uploadFile($_FILES["image"], 'item');
                    if (!isFilename($imageFileName)) {
                        $info = '<div class="alert alert-danger" role="alert">' . $imageFileName . '</div>';
                    } else {
                        // Insert data into database
                        $insert = insertStoreItem($store, $itemName, $itemAmount, $itemID, $itemDescription, $defaultPrice, $salePrice, $category, $amountPurchased, $hotDeal, $imageFileName);
                        if ($insert['success']) {
                            $info = '<div class="alert alert-success" role="alert">' . $insert['data'] . '</div>';
                        } else {
                            // Delete uploaded files if database insert fails
                            unlink("uploads/$imageFileName");
                            $info = '<div class="alert alert-danger" role="alert">' . $insert['data'] . '</div>';
                        }
                    }
                } else if (isset($_REQUEST['deleteStoreItem'])) {
                    $id = sanitize($_REQUEST['deleteStoreItem']);
                    $StoreCheck = getStoreItemById($id);
                    if ($StoreCheck['success'] == true) {
                        $imageAddr = $StoreCheck['data']['image'];
                        unlink("uploads/$imageAddr");
                        $delete = deleteStoreItemById($id);
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
                            <h2 class="display1 text-center">Stores Items</h2>
                        </div>
                        <?php echo $info; ?>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Store Items</h4>
                                        <form class="forms-sample" method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="store">Store</label>
                                                        <select class="form-control" name="store" id="store" required>
                                                            <option value="" disabled>Select store</option>
                                                            <!-- PHP code to fetch and display all stores as options -->
                                                            <?php
                                                            // Assuming getAllStores() function retrieves all stores from the database
                                                            $allStores = getAllStores();
                                                            if (!empty($allStores['data'])) {
                                                                foreach ($allStores['data'] as $store) {
                                                                    echo '<option value="' . $store['id'] . '">' . $store['store_name'] . '</option>';
                                                                }
                                                            } else {
                                                                echo '<option value="" selected disabled>No store present yet!</option>';
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="item_name">Item Name</label>
                                                        <input type="text" class="form-control" name="item_name"
                                                            id="item_name" required placeholder="Enter item name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="item_amount">Item Amount</label>
                                                        <input type="number" class="form-control" name="item_amount"
                                                            id="item_amount" required placeholder="Enter item amount"
                                                            value="1">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="item_id">Item ID</label>
                                                        <input type="number" class="form-control" name="item_id"
                                                            id="item_id" required placeholder="Enter item ID" value="1">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="item_description">Item Description</label>
                                                        <textarea rows="3" class="form-control" name="item_description"
                                                            id="item_description" required
                                                            placeholder="Enter item description"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="default_price">Default Price</label>
                                                        <input type="number" class="form-control" name="default_price"
                                                            id="default_price" required
                                                            placeholder="Enter default price" value="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sale_price">Sale Price</label>
                                                        <input type="number" class="form-control" name="sale_price"
                                                            id="sale_price" required placeholder="Enter sale price"
                                                            value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="category">Category</label>
                                                        <input type="text" class="form-control" name="category"
                                                            id="category" placeholder="Enter category">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="amount_purchased">Amount Purchased</label>
                                                        <input type="number" class="form-control"
                                                            name="amount_purchased" id="amount_purchased"
                                                            placeholder="Enter amount purchased" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="hot_deal">Hot Deal</label>
                                                        <input type="number" class="form-control" name="hot_deal"
                                                            id="hot_deal" placeholder="Enter hot deal" value="0">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="image">Image</label>
                                                        <input type="file" class="form-control" name="image" id="image">
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
                        <div class="row">
                            <?php
                            // Retrieve all store items
                            $allStoreItems = getAllStoreItems();
                            if ($allStoreItems['success']) {
                                $storeItems = $allStoreItems['data'];
                            } else {
                                $storeItems = []; // Empty array if no store items found
                            }
                            ?>
                            <div class="col-lg-12 grid-margin stretch-card">
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
                                            <?php echo getPagination('store_items'); ?>
                                        </div>
                                        <h4 class="card-title">All Store Items</h4>
                                        <div class="table-responsive">
                                            <table class="table dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Store</th>
                                                        <th>Item</th>
                                                        <th>Item Amount</th>
                                                        <th>Item ID</th>
                                                        <th>Item Description</th>
                                                        <th>Default Price</th>
                                                        <th>Sale Price</th>
                                                        <th>Category</th>
                                                        <th>Amount Purchased</th>
                                                        <th>Hot Deal</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($storeItems as $storeItem):
                                                        $storeId = $storeItem['store'];
                                                        $storeName = getStoreById($storeId);
                                                        $storeItem['store'] = $storeName['data']['store_name'];
                                                        $hotDeal = ($storeItem['hot_deal'] == 1) ? 'Yes' : 'No';
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $storeItem['id']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $storeItem['store']; ?>
                                                            </td>
                                                            <td>
                                                                <img src="uploads/<?php echo $storeItem['image']; ?>"
                                                                    alt="Image" class="table-img">
                                                                <span>
                                                                    <?php echo $storeItem['item_name']; ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <?php echo $storeItem['item_amount']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $storeItem['item_id']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $storeItem['item_description']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $storeItem['default_price']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $storeItem['sale_price']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $storeItem['category']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $storeItem['amount_purchased']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $hotDeal; ?>
                                                            </td>
                                                            <td>
                                                                <a href="edit-store-item.php?id=<?php echo $storeItem['id']; ?>"
                                                                    class="btn btn-dark mr-2">Edit</a>
                                                                <a href="?deleteStoreItem=<?php echo $storeItem['id']; ?>"
                                                                    class="btn btn-danger deleteBtn">Delete</a>
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