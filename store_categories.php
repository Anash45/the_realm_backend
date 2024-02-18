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
                    $categoryName = $_POST['category_name'];
                    $categoryImage = $_FILES['category_image'];

                    // Validate form data
                    if (empty($store) || empty($categoryName) || empty($categoryImage)) {
                        $info = '<div class="alert alert-danger" role="alert">All fields are required.</div>';
                    } else {
                        // Upload category image
                        $categoryImageFileName = uploadFile($categoryImage, 'category_image');
                        if (!isFilename($categoryImageFileName)) {
                            $info = '<div class="alert alert-danger" role="alert">' . $categoryImageFileName . '</div>';
                        } else {
                            // Insert data into database
                            $insert = insertCategory($store, $categoryName, $categoryImageFileName);
                            if ($insert['success']) {
                                $info = '<div class="alert alert-success" role="alert">' . $insert['data'] . '</div>';
                            } else {
                                // Delete uploaded file if database insert fails
                                unlink("uploads/$categoryImageFileName");
                                $info = '<div class="alert alert-danger" role="alert">' . $insert['data'] . '</div>';
                            }
                        }
                    }
                } else if (isset($_REQUEST['deleteCategory'])) {
                    $id = sanitize($_REQUEST['deleteCategory']);
                    $categoryCheck = getCategoryById($id);
                    if ($categoryCheck['success'] == true) {
                        $categoryImageAddr = $categoryCheck['data']['category_image'];
                        unlink("uploads/$categoryImageAddr");
                        $delete = deleteCategoryById($id);
                        if ($delete['success'] == true) {
                            $info = '<div class="alert alert-success" role="alert">' . $delete['data'] . '</div>';
                        } else {
                            $info = '<div class="alert alert-danger" role="alert">' . $delete['data'] . '</div>';
                        }
                    }
                }
                ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Stores Categories</h2>
                        </div>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <?php echo $info; ?>
                                        <h4 class="card-title">Add Store Category</h4>
                                        <form class="forms-sample" method="POST" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="store">Store</label>
                                                <input type="text" class="form-control" name="store" id="store" required
                                                    placeholder="Store Name">
                                            </div>
                                            <div class="form-group">
                                                <label for="categoryName">Category Name</label>
                                                <input type="text" class="form-control" name="category_name"
                                                    id="categoryName" required placeholder="Category Name">
                                            </div>
                                            <div class="form-group">
                                                <label for="categoryImage">Category Image</label>
                                                <input type="file" class="form-control" name="category_image"
                                                    id="categoryImage" required>
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
                                            <?php echo getPagination('store_categories'); ?>
                                        </div>
                                        <?php
                                        $allCategories = getAllStoreCategories();
                                        $categories = $allCategories['data'];
                                        ?>
                                        <h4 class="card-title">All Store Categories</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Store</th>
                                                        <th>Category Name</th>
                                                        <th>Category Image</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (count($categories) > 0) {
                                                        foreach ($categories as $category) {
                                                            echo '<tr>
                                    <td>' . $category['id'] . '</td>
                                    <td>' . $category['store'] . '</td>
                                    <td>' . $category['category_name'] . '</td>
                                    <td><img src="uploads/' . $category['category_image'] . '" alt="Category Image" class="table-img"></td>
                                    <td>
                                        <a href="edit-category.php?id=' . $category['id'] . '" class="btn btn-dark mr-2">Edit</a>
                                        <a href="?deleteCategory=' . $category['id'] . '" class="btn btn-danger deleteBtn">Delete</a>
                                    </td>
                                </tr>';
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="5"><div class="alert alert-danger">No categories found!</div></td></tr>';
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