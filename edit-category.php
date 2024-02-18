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
                    $storeId = $_POST['store'];
                    $categoryName = $_POST['category_name'];
                    $categoryId = $_GET['id'];

                    // Initialize error and info variables
                    $error = "";
                    $info = "";

                    // Check if category name is not empty
                    if (!empty($categoryName)) {
                        // Initialize update data array
                        $updateData = array();

                        // Check if category image file is uploaded
                        if (!empty($_FILES['category_image']['name'])) {
                            $categoryImage = uploadFile($_FILES['category_image'], 'category_image');
                            if (!isFilename($categoryImage)) {
                                $error .= '<div class="alert alert-warning" role="alert">' . $categoryImage . '</div>';
                                // Handle error uploading category image
                            } else {
                                $updateData['category_image'] = $categoryImage;
                            }
                        }

                        // Update category name
                        $updateData['category_name'] = $categoryName;

                        // Update store ID
                        $updateData['store'] = $storeId;

                        // Update category information
                        $conditions = array('id' => $categoryId);

                        // Update the category
                        $updateResult = updateStoreCategory($updateData, $conditions);

                        if ($updateResult['success']) {
                            $info = '<div class="alert alert-success" role="alert">Category information updated successfully.</div>';
                            // Handle successful update
                        } else {
                            $error = '<div class="alert alert-danger" role="alert">Error updating category information: ' . $updateResult['data'] . '</div>';
                            // Handle update error
                        }
                    } else {
                        $error = '<div class="alert alert-warning" role="alert">Category name is required.</div>';
                        // Handle empty category name
                    }
                }
                ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Stores Categories</h2>
                        </div>
                        <?php
                        if (isset($_REQUEST['id'])) {
                            $id = $_REQUEST['id'];
                            $storeCategoryData = getCategoryById($id);
                            if ($storeCategoryData['success'] == true) {
                                $category = $storeCategoryData['data'];
                                ?>
                                <div class="row">
                                    <div class="col-lg-6 mx-auto mb-3">
                                        <a href="store_categories.php" class="btn btn-sm btn-primary"><i class="mdi mdi-arrow-left"></i>
                                            Back</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 grid-margin stretch-card mx-auto">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Edit Store Category</h4>
                                                <?php echo $info; ?>
                                                <form class="forms-sample" action="?id=<?php echo $id ?>" method="POST"
                                                    enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for="store">Store</label>
                                                        <input type="text" class="form-control" name="store" id="store"
                                                            value="<?php echo $category['store']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="categoryName">Category Name</label>
                                                        <input type="text" class="form-control" name="category_name"
                                                            id="categoryName" value="<?php echo $category['category_name']; ?>"
                                                            required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="categoryImage">Category Image</label>
                                                        <input type="file" class="form-control" name="category_image"
                                                            id="categoryImage">
                                                        <p class="text-white"><small>Leave file fields if don't want to update
                                                                the current image.</small></p>
                                                        <?php if (!empty($category['category_image'])): ?>
                                                            <p class="text-white mt-2">Current Image: <img
                                                                    src="uploads/<?php echo $category['category_image']; ?>"
                                                                    alt="Current Category Image" class="table-img"></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <button class="btn btn-dark mr-2" type="reset">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                echo '<div class="alert alert-danger">' . $storeCategoryData['data'] . '</div>';
                            }
                        }
                        ?>
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