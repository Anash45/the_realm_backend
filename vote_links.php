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
                <?php
                $info = '';
                if (isset($_POST['submit'])) {
                    // Retrieve form data
                    $title = $_POST['title'];
                    $url = $_POST['url'];
                    $siteId = $_POST['site_id'];
                    $active = $_POST['active'];

                    // Insert the vote link into the database
                    $insertResult = insertVoteLink($title, $url, $siteId, $active);

                    // Check if the insertion was successful
                    if ($insertResult['success']) {
                        $info = '<div class="alert alert-success" role="alert">' . $insertResult['data'] . '</div>';
                    } else {
                        $info = '<div class="alert alert-danger" role="alert">' . $insertResult['data'] . '</div>';
                    }
                } else if (isset($_REQUEST['deleteVoteLink'])) {
                    $id = sanitize($_REQUEST['deleteVoteLink']);
                    $DonationCheck = getVoteLinkById($id);
                    if ($DonationCheck['success'] == true) {
                        $delete = deleteVoteLinkById($id);
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
                            <h2 class="display1 text-center">Vote Links</h2>
                        </div>
                        <?php echo $info; ?>
                        <div class="row">
                            <div class="col-md-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Vote Link</h4>
                                        <form method="POST" class="forms-sample">
                                            <div class="form-group">
                                                <label>Title *</label>
                                                <input type="text" class="form-control" name="title" required>
                                            </div>
                                            <div class="form-group">
                                                <label>URL *</label>
                                                <input type="text" class="form-control" name="url" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Site ID *</label>
                                                <input type="number" class="form-control" name="site_id" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Active *</label>
                                                <select class="form-control" name="active" required>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                            <button class="btn btn-dark" type="reset">Cancel</button>
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 grid-margin stretch-card">
                                <?php
                                // Retrieve all vote links
                                $allVoteLinks = getAllVoteLinks();
                                $voteLinks = $allVoteLinks['data'];
                                ?>
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
                                            <?php echo getPagination('vote_links'); ?>
                                        </div>
                                        <h4 class="card-title">Vote Links</h4>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Title</th>
                                                        <th>URL</th>
                                                        <th>Site ID</th>
                                                        <th>Active</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (count($voteLinks) > 0) {
                                                        foreach ($voteLinks as $voteLink) {
                                                            $actionBtns = '<a href="edit-vote-link.php?id=' . $voteLink['id'] . '" class="btn btn-dark mr-2">Edit</a>';
                                                            $actionBtns .= '<a href="?deleteVoteLink=' . $voteLink['id'] . '" class="btn btn-danger deleteBtn">Delete</a>';
                                                            echo '<tr>
                                    <td>' . $voteLink['id'] . '</td>
                                    <td>' . $voteLink['title'] . '</td>
                                    <td>' . $voteLink['url'] . '</td>
                                    <td>' . $voteLink['site_id'] . '</td>
                                    <td>' . $voteLink['active'] . '</td>
                                    <td>' . $actionBtns . '</td>
                                </tr>';
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="6" class="text-center">No vote links found!</td></tr>';
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