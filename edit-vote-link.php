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
                    $id = $_GET['id']; // Get the ID of the vote link to update
                
                    // Retrieve form data
                    $title = $_POST['title'];
                    $url = $_POST['url'];
                    $siteId = $_POST['site_id'];
                    $active = $_POST['active']; // Check if the vote link is active
                
                    // Update vote link information
                    $updateData = array(
                        'title' => $title,
                        'url' => $url,
                        'site_id' => $siteId,
                        'active' => $active
                    );

                    // Define conditions to update the specific vote link
                    $conditions = array('id' => $id);

                    // Update the vote link using the updateVoteLink function
                    $updateResult = updateVoteLink($updateData, $conditions);

                    // Check if the update was successful
                    if ($updateResult['success']) {
                        $info = '<div class="alert alert-success" role="alert">Vote link updated successfully.</div>';
                    } else {
                        $error = '<div class="alert alert-danger" role="alert">Error updating vote link: ' . $updateResult['data'] . '</div>';
                    }
                }
                ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <div class="pb-4">
                            <h2 class="display1 text-center">Vote Links</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mx-auto mb-3">
                                <a href="vote_links.php" class="btn btn-sm btn-primary"><i
                                        class="mdi mdi-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <?php echo $info; ?>
                        <?php if (isset($_REQUEST['id'])) {
                            $id = $_REQUEST['id'];
                            $VoteLink = getVoteLinkById($id);
                            if ($VoteLink['success'] == true) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Add Vote Link</h4>
                                                <form method="POST" class="forms-sample">
                                                    <div class="form-group">
                                                        <label>Title *</label>
                                                        <input type="text" value="<?php echo $VoteLink['data']['title'] ?>"
                                                            class="form-control" name="title" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>URL *</label>
                                                        <input type="text" value="<?php echo $VoteLink['data']['url'] ?>"
                                                            class="form-control" name="url" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Site ID *</label>
                                                        <input type="number" value="<?php echo $VoteLink['data']['site_id'] ?>"
                                                            class="form-control" name="site_id" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Active *</label>
                                                        <select class="form-control" id="vote_link_active" name="active"
                                                            required>
                                                            <option value="1" <?php if ($VoteLink['data']['active'] == 1){echo 'selected';} ?>>Yes</option>
                                                            <option value="0" <?php if ($VoteLink['data']['active'] == 0){echo 'selected';} ?>>No</option>
                                                        </select>
                                                    </div>
                                                    <button class="btn btn-dark" type="reset">Cancel</button>
                                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
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