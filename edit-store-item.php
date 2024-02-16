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
                    $storeName = $_POST['store'];
                    $itemName = $_POST['item_name'];
                    $itemAmount = $_POST['item_amount'];
                    $itemID = $_POST['item_id'];
                    $itemDescription = $_POST['item_description'];
                    $defaultPrice = $_POST['default_price'];
                    $salePrice = $_POST['sale_price'];
                    $category = $_POST['category'];
                    $amountPurchased = $_POST['amount_purchased'];
                    $hotDeal = $_POST['hot_deal'];
                    $id = $_GET['id'];
                    
                    $storeItem1 = getStoreItemById($id);
                    $imageAddr = $storeItem1['data']['image'];

                    // Initialize error and info variables
                    $error = "";
                    $info = "";

                    // Check if there are any changes in the file upload fields
                    $imageFileName = "";
                    if (!empty($_FILES['image']['name'])) {
                        $imageFileName = uploadFile($_FILES['image'], 'item');
                        if (!isFilename($imageFileName)) {
                            $error .= '<div class="alert alert-warning" role="alert">' . $imageFileName . '</div>';
                            // Handle error uploading logo
                        }else {
                            unlink('uploads/'.$imageAddr);
                        }
                    }

                    // Update store item information
                    $updateData = array();
                    if (!empty($storeName)) {
                        $updateData['store'] = $storeName;
                    }
                    if (!empty($itemName)) {
                        $updateData['item_name'] = $itemName;
                    }
                    if (!empty($itemAmount)) {
                        $updateData['item_amount'] = $itemAmount;
                    }
                    if (!empty($itemID)) {
                        $updateData['item_id'] = $itemID;
                    }
                    if (!empty($itemDescription)) {
                        $updateData['item_description'] = $itemDescription;
                    }
                    if (!empty($defaultPrice)) {
                        $updateData['default_price'] = $defaultPrice;
                    }
                    if (!empty($salePrice)) {
                        $updateData['sale_price'] = $salePrice;
                    }
                    if (!empty($category)) {
                        $updateData['category'] = $category;
                    }
                    if (!empty($amountPurchased)) {
                        $updateData['amount_purchased'] = $amountPurchased;
                    }
                    if (!empty($hotDeal)) {
                        $updateData['hot_deal'] = $hotDeal;
                    }
                    if (!empty($imageFileName)) {
                        $updateData['image'] = $imageFileName;
                    }

                    $conditions = array('id' => $id);

                    // Check if there's anything to update
                    if (!empty($updateData)) {
                        $updateResult = updateStoreItem($updateData, $conditions);
                        if ($updateResult['success']) {
                            $info = '<div class="alert alert-success" role="alert">Store item information updated successfully.</div>';
                            // Handle successful update
                        } else {
                            $error = '<div class="alert alert-danger" role="alert">Error updating store item information: ' . $updateResult['data'] . '</div>';
                            // Handle update error
                        }
                    } else {
                        $error = '<div class="alert alert-warning" role="alert">No changes detected.</div>';
                        // Handle no changes detected
                    }
                }


                ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Stores</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mx-auto mb-3">
                                <a href="store_items.php" class="btn btn-sm btn-primary"><i
                                        class="mdi mdi-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <?php if (isset($_REQUEST['id'])) {
                            $id = $_REQUEST['id'];
                            $storeItem = getStoreItemById($id);
                            if ($storeItem['success'] == true) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12 grid-margin stretch-card mx-auto">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Edit Store Item</h4>
                                                <?php echo $info;
                                                echo $error; ?>
                                                <form class="forms-sample" action="?id=<?php echo $id; ?>" method="POST"
                                                    enctype="multipart/form-data">
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
                                                                            $selected = ($store['id'] == $storeItem['data']['store']) ? 'selected' : '';
                                                                            echo '<option value="' . $store['id'] . '" ' . $selected . '>' . $store['store_name'] . '</option>';
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
                                                                    id="item_name"
                                                                    value="<?php echo $storeItem['data']['item_name']; ?>"
                                                                    required placeholder="Enter item name">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="item_amount">Item Amount</label>
                                                                <input type="number" class="form-control" name="item_amount"
                                                                    id="item_amount"
                                                                    value="<?php echo $storeItem['data']['item_amount']; ?>"
                                                                    required placeholder="Enter item amount" value="1">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="item_id">Item ID</label>
                                                                <input type="number" class="form-control" name="item_id"
                                                                    id="item_id"
                                                                    value="<?php echo $storeItem['data']['item_id']; ?>"
                                                                    required placeholder="Enter item ID" value="1">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="item_description">Item Description</label>
                                                                <textarea rows="3" class="form-control" name="item_description"
                                                                    id="item_description" required
                                                                    placeholder="Enter item description"><?php echo $storeItem['data']['item_description']; ?></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="default_price">Default Price</label>
                                                                <input type="number" class="form-control" name="default_price"
                                                                    id="default_price"
                                                                    value="<?php echo $storeItem['data']['default_price']; ?>"
                                                                    required placeholder="Enter default price" value="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="sale_price">Sale Price</label>
                                                                <input type="number" class="form-control" name="sale_price"
                                                                    id="sale_price"
                                                                    value="<?php echo $storeItem['data']['sale_price']; ?>"
                                                                    required placeholder="Enter sale price" value="0">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="category">Category</label>
                                                                <input type="text" class="form-control" name="category"
                                                                    id="category"
                                                                    value="<?php echo $storeItem['data']['category']; ?>"
                                                                    placeholder="Enter category">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="amount_purchased">Amount Purchased</label>
                                                                <input type="number" class="form-control"
                                                                    name="amount_purchased" id="amount_purchased"
                                                                    value="<?php echo $storeItem['data']['amount_purchased']; ?>"
                                                                    placeholder="Enter amount purchased" value="0">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="hot_deal">Hot Deal</label>
                                                                <input type="number" class="form-control" name="hot_deal"
                                                                    id="hot_deal"
                                                                    value="<?php echo $storeItem['data']['hot_deal']; ?>"
                                                                    placeholder="Enter hot deal" value="0">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="image">Image</label>
                                                                <input type="file" class="form-control" name="image" id="image">
                                                                <p class="text-white"><small>Leave file fields if don't want to
                                                                        update the current image.</small></p>
                                                                <img src="uploads/<?php echo $storeItem['data']['image'] ?>"
                                                                    alt="" class="table-img">
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