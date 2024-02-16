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
                    $storeName = $_POST['store_name'];
                    $color = $_POST['color'];
                    $id = $_GET['id'];

                    $store1 = getStoreById($id);
                    $logoAddr = $store1['data']['logo'];
                    $faviconAddr = $store1['data']['logo'];

                    // Initialize error and info variables
                    $error = "";
                    $info = "";

                    // Check if logo file field is not empty
                    $logoFileName = "";
                    if (!empty($_FILES['logo']['name'])) {
                        $logoFileName = uploadFile($_FILES['logo'], 'logo');
                        if (!isFilename($logoFileName)) {
                            $error .= '<div class="alert alert-warning" role="alert">'.$logoFileName.'</div>';
                            // Handle error uploading logo
                        }else {
                            unlink('uploads/'.$logoAddr);
                        }
                    }

                    // Check if favicon file field is not empty
                    $faviconFileName = "";
                    if (!empty($_FILES['favicon']['name'])) {
                        $faviconFileName = uploadFile($_FILES['favicon'], 'logo');
                        if (!isFilename($faviconFileName)) {
                            $error .= '<div class="alert alert-warning" role="alert">'.$faviconFileName.'</div>';
                            // Handle error uploading favicon
                        }else {
                            unlink('uploads/'.$logoAddr);
                        }
                    }

                    // Update store information
                    $updateData = array();
                    if (!empty($storeName)) {
                        $updateData['store_name'] = $storeName;
                    }
                    if (!empty($color)) {
                        $updateData['color'] = $color;
                    }
                    if (!empty($logoFileName)) {
                        $updateData['logo'] = $logoFileName;
                    }
                    if (!empty($faviconFileName)) {
                        $updateData['favicon'] = $faviconFileName;
                    }

                    $conditions = array('id'=>$id);

                    // Check if there's anything to update
                    if (!empty($updateData)) {
                        $updateResult = updateStore($updateData, $conditions);
                        if ($updateResult['success']) {
                            $info = '<div class="alert alert-success" role="alert">Store information updated successfully.</div>';
                            // Handle successful update
                        } else {
                            $error = '<div class="alert alert-danger" role="alert">Error updating store information: ' . $updateResult['data'] . '</div>';
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
                            <div class="col-lg-6 mx-auto mb-3">
                                <a href="stores.php" class="btn btn-sm btn-primary"><i class="mdi mdi-arrow-left"></i>
                                    Back</a>
                            </div>
                        </div>
                        <?php if (isset($_REQUEST['id'])) {
                            $id = $_REQUEST['id'];
                            $store = getStoreById($id);
                            if ($store['success'] == true) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6 grid-margin stretch-card mx-auto">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Edit Store</h4>
                                                <?php echo $info; echo $error; ?>
                                                <form class="forms-sample" action="?id=<?php echo $id ?>" method="POST" enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="storeName">Store Name</label>
                                                        <input type="text" class="form-control" name="store_name" id="storeName"
                                                            required value="<?php echo $store['data']['store_name'] ?>"
                                                            placeholder="Enter store name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="logo">Logo</label>
                                                        <input type="file" class="form-control" name="logo" id="logo">
                                                        <p class="text-white"><small>Leave file fields if don't want to update
                                                                the current image.</small></p>
                                                        <img src="uploads/<?php echo $store['data']['logo'] ?>" alt=""
                                                            class="table-img">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="favicon">Favicon</label>
                                                        <input type="file" class="form-control" name="favicon" id="favicon">
                                                        <p class="text-white"><small>Leave file fields if don't want to update
                                                                the current image.</small></p>
                                                        <img src="uploads/<?php echo $store['data']['favicon'] ?>" alt=""
                                                            class="table-img">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="color">Color</label>
                                                        <input type="color" class="form-control" name="color" id="color"
                                                            required value="<?php echo $store['data']['color'] ?>">
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