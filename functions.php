<?php
session_start();
// Function to establish a database connection
function connectToDatabase()
{
    $host = 'localhost';
    $username = 'root';
    $password = 'root';
    $database = 'the_realm_db';

    $connection = new mysqli($host, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}

function isLoggedIn()
{
    if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['role'])) {
        // Get user ID from session
        $userId = $_SESSION['user_id'];

        // Connect to your database
        $connection = connectToDatabase();

        // Check if the user ID exists in the online_users table
        $checkQuery = "SELECT * FROM online_users WHERE account_id = $userId";
        $checkResult = $connection->query($checkQuery);

        // Close database connection
        $connection->close();

        // Return true if user ID exists in online_users table
        return $checkResult->num_rows > 0;
    } else {
        return false;
    }
}

function isAdmin()
{
    // Check if user is logged in and has the role of admin
    return isLoggedIn() && $_SESSION['role'] === 'Admin';
}

function isUser()
{
    // Check if user is logged in and has the role of user
    return isLoggedIn() && $_SESSION['role'] === 'User';
}

function updateOnlineStatus()
{
    if (isset($_SESSION['user_id']) && isset($_SESSION['username']) && isset($_SESSION['role'])) {
        // Get user ID and last activity time from session
        $userId = $_SESSION['user_id'];
        $lastActivity = date('Y-m-d H:i:s'); // You can use any format suitable for your database

        // Connect to your database
        $connection = connectToDatabase();

        // Check if the user ID already exists in the online_users table
        $checkQuery = "SELECT * FROM online_users WHERE account_id = $userId";
        $checkResult = $connection->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            // User already exists, update the last_activity column
            $updateQuery = "UPDATE online_users SET last_activity = '$lastActivity' WHERE account_id = $userId";
            $connection->query($updateQuery);
        } else {
            // User doesn't exist, insert a new record
            $insertQuery = "INSERT INTO online_users (account_id, last_activity) VALUES ($userId, '$lastActivity')";
            $connection->query($insertQuery);
        }
    }
}

function logout()
{
    // Destroy all sessions
    session_unset();
    session_destroy();

    // Remove user from online status
    removeUserFromOnline();
    
    // Redirect to the login page or any other desired page
    header("Location: login.php");
    exit();
}

// Function to remove user from online status
function removeUserFromOnline()
{
    if (isset($_SESSION['user_id'])) {
        // Get the user ID
        $userId = $_SESSION['user_id'];

        // Connect to the database
        $connection = connectToDatabase();

        // Delete the user from the online_users table
        $deleteQuery = "DELETE FROM online_users WHERE user_id = $userId";
        $connection->query($deleteQuery);

        // Close the database connection
        $connection->close();
    }
}

function uploadFile($file, $fieldName)
{
    $targetDirectory = "uploads/"; // Directory where files will be uploaded
    $targetFile = $targetDirectory . basename($file["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($targetFile)) {
        return "File already exists.";
    }

    // Check file size
    if ($file["size"] > 5000000) { // Adjust as needed
        return "File is too large.";
    }

    // Allow certain file formats
    $allowedFormats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($fileType, $allowedFormats)) {
        return "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }

    // Generate a unique name for the file
    $fileName = uniqid($fieldName . '_') . '.' . $fileType;

    // Move the file to the target directory
    if (move_uploaded_file($file["tmp_name"], $targetDirectory . $fileName)) {
        return $fileName; // Return the filename
    } else {
        return "Error uploading file.";
    }
}


function isFilename($filename)
{
    // Define a regular expression pattern for valid filenames
    $pattern = '/^[a-zA-Z0-9-_\.]+\.[a-zA-Z]{2,4}$/';

    // Check if the filename matches the pattern
    if (preg_match($pattern, $filename)) {
        return true; // Filename is valid
    } else {
        return false; // Filename is invalid
    }
}

function sanitize($input)
{
    $conn = connectToDatabase();

    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitize($value);
        }
    } else {
        // Remove leading/trailing whitespace
        $input = trim($input);
        // Escape special characters to prevent SQL injection
        $input = mysqli_real_escape_string($conn, $input);
    }

    return $input;
}

// Function to insert a new account
function insertAccount($username, $password, $role)
{
    $connection = connectToDatabase();

    // Check if the email already exists
    $existingAccount = $connection->query("SELECT * FROM accounts WHERE username='$username'")->fetch_assoc();
    if ($existingAccount) {
        return array("success" => false, "data" => "Username already exists.");
    }

    $query = "INSERT INTO accounts (username, password, role) VALUES ('$username', '$password', '$role')";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "New record created successfully.");
    } else {
        return array("success" => false, "data" => "Error: " . $query . "<br>" . $connection->error);
    }
}

// Function to delete an account by ID
function deleteAccountById($id)
{
    $connection = connectToDatabase();

    $query = "DELETE FROM accounts WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to update an account
function updateAccount($updateData, $conditions)
{
    $connection = connectToDatabase();

    $updateString = "";
    foreach ($updateData as $key => $value) {
        $updateString .= "$key='$value',";
    }
    $updateString = rtrim($updateString, ',');

    $conditionString = "";
    foreach ($conditions as $key => $value) {
        $conditionString .= "$key='$value' AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    $query = "UPDATE accounts SET $updateString WHERE $conditionString";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record updated successfully.");
    } else {
        return array("success" => false, "data" => "Error updating record: " . $connection->error);
    }
}

// Function to get all accounts
function getAllAccounts()
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM accounts";
    $result = $connection->query($query);

    $accounts = [];
    while ($row = $result->fetch_assoc()) {
        $accounts[] = $row;
    }
    return array("success" => true, "data" => $accounts);
}

// Function to get an account by ID
function getAccountById($id)
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM accounts WHERE id=$id";
    $result = $connection->query($query);

    if ($result->num_rows == 1) {
        $account = $result->fetch_assoc();
        return array("success" => true, "data" => $account);
    } else {
        return array("success" => false, "data" => "Account not found.");
    }
}

// Function to get an account by username
function getAccountByUsername($username)
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM accounts WHERE username='$username'";
    $result = $connection->query($query);

    if ($result->num_rows == 1) {
        $account = $result->fetch_assoc();
        return array("success" => true, "data" => $account);
    } else {
        return array("success" => false, "data" => "Account not found.");
    }
}

// Function to insert a new finalized vote
function insertVote($serverName, $username, $toplist, $ipAddress, $rewardAmount, $timeVoted, $claimed, $vpn)
{
    $connection = connectToDatabase();

    $query = "INSERT INTO finalized_votes (server_name, username, toplist, ip_address, reward_amount, time_voted, claimed, vpn) VALUES ('$serverName', '$username', '$toplist', '$ipAddress', $rewardAmount, '$timeVoted', $claimed, $vpn)";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "New record created successfully.");
    } else {
        return array("success" => false, "data" => "Error: " . $query . "<br>" . $connection->error);
    }
}

// Function to delete a finalized vote by ID
function deleteVoteById($id)
{
    $connection = connectToDatabase();

    $query = "DELETE FROM finalized_votes WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to update a finalized vote
function updateVote($updateData, $conditions)
{
    $connection = connectToDatabase();

    $updateString = "";
    foreach ($updateData as $key => $value) {
        $updateString .= "$key='$value',";
    }
    $updateString = rtrim($updateString, ',');

    $conditionString = "";
    foreach ($conditions as $key => $value) {
        $conditionString .= "$key='$value' AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    $query = "UPDATE finalized_votes SET $updateString WHERE $conditionString";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record updated successfully.");
    } else {
        return array("success" => false, "data" => "Error updating record: " . $connection->error);
    }
}

// Function to get all finalized votes
function getAllVotes()
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM finalized_votes";
    $result = $connection->query($query);

    $votes = [];
    while ($row = $result->fetch_assoc()) {
        $votes[] = $row;
    }
    return array("success" => true, "data" => $votes);
}

// Function to get a finalized vote by ID
function getVoteById($id)
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM finalized_votes WHERE id=$id";
    $result = $connection->query($query);

    if ($result->num_rows == 1) {
        $vote = $result->fetch_assoc();
        return array("success" => true, "data" => $vote);
    } else {
        return array("success" => false, "data" => "Vote not found.");
    }
}

// Function to get finalized votes by server name
function getVotesByServerName($serverName)
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM finalized_votes WHERE server_name='$serverName'";
    $result = $connection->query($query);

    $votes = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $votes[] = $row;
        }
        return array("success" => true, "data" => $votes);
    } else {
        return array("success" => false, "data" => "No votes found for the specified server name.");
    }
}

// Function to insert a new store category
function insertStoreCategory($store, $categoryName, $categoryImage)
{
    $connection = connectToDatabase();

    $query = "INSERT INTO store_categories (store, category_name, category_image) VALUES ('$store', '$categoryName', '$categoryImage')";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "New record created successfully.");
    } else {
        return array("success" => false, "data" => "Error: " . $query . "<br>" . $connection->error);
    }
}

// Function to delete a store category by ID
function deleteStoreCategoryById($id)
{
    $connection = connectToDatabase();

    $query = "DELETE FROM store_categories WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to update a store category
function updateStoreCategory($updateData, $conditions)
{
    $connection = connectToDatabase();

    $updateString = "";
    foreach ($updateData as $key => $value) {
        $updateString .= "$key='$value',";
    }
    $updateString = rtrim($updateString, ',');

    $conditionString = "";
    foreach ($conditions as $key => $value) {
        $conditionString .= "$key='$value' AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    $query = "UPDATE store_categories SET $updateString WHERE $conditionString";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record updated successfully.");
    } else {
        return array("success" => false, "data" => "Error updating record: " . $connection->error);
    }
}

// Function to get all store categories
function getAllStoreCategories()
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM store_categories";
    $result = $connection->query($query);

    $storeCategories = [];
    while ($row = $result->fetch_assoc()) {
        $storeCategories[] = $row;
    }
    return array("success" => true, "data" => $storeCategories);
}

// Function to get a store category by ID
function getStoreCategoryById($id)
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM store_categories WHERE id=$id";
    $result = $connection->query($query);

    $storeCategory = null;
    if ($result->num_rows == 1) {
        $storeCategory = $result->fetch_assoc();
        return array("success" => true, "data" => $storeCategory);
    } else {
        return array("success" => false, "data" => "Store category not found.");
    }
}

// Function to get store categories by store name
function getStoreCategoriesByStore($store)
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM store_categories WHERE store='$store'";
    $result = $connection->query($query);

    $storeCategories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $storeCategories[] = $row;
        }
        return array("success" => true, "data" => $storeCategories);
    } else {
        return array("success" => false, "data" => "No store categories found for the specified store.");
    }
}

// Function to insert a new donation
function insertDonation($donorName, $amount, $date, $ipAddress)
{
    $connection = connectToDatabase();

    $query = "INSERT INTO donations (donor_name, amount, date, ip_address) VALUES ('$donorName', $amount, '$date', '$ipAddress')";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "New record created successfully.");
    } else {
        return array("success" => false, "data" => "Error: " . $query . "<br>" . $connection->error);
    }
}

// Function to delete a donation by ID
function deleteDonationById($id)
{
    $connection = connectToDatabase();

    $query = "DELETE FROM donations WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to update a donation
function updateDonation($updateData, $conditions)
{
    $connection = connectToDatabase();

    $updateString = "";
    foreach ($updateData as $key => $value) {
        $updateString .= "$key='$value',";
    }
    $updateString = rtrim($updateString, ',');

    $conditionString = "";
    foreach ($conditions as $key => $value) {
        $conditionString .= "$key='$value' AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    $query = "UPDATE donations SET $updateString WHERE $conditionString";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record updated successfully.");
    } else {
        return array("success" => false, "data" => "Error updating record: " . $connection->error);
    }
}

// Function to get all donations
function getAllDonations()
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM donations";
    $result = $connection->query($query);

    $donations = [];
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
    return array("success" => true, "data" => $donations);
}

// Function to get a donation by ID
function getDonationById($id)
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM donations WHERE id=$id";
    $result = $connection->query($query);

    $donation = null;
    if ($result->num_rows == 1) {
        $donation = $result->fetch_assoc();
        return array("success" => true, "data" => $donation);
    } else {
        return array("success" => false, "data" => "Donation not found.");
    }
}

function getAllActiveSessions()
{
    $connection = connectToDatabase();

    $query = "SELECT online_users.id, accounts.username, online_users.last_activity FROM online_users JOIN accounts ON online_users.account_id = accounts.id";
    $result = $connection->query($query);

    $sessions = [];
    while ($row = $result->fetch_assoc()) {
        $sessions[] = $row;
    }
    return array("success" => true, "data" => $sessions);
}
function deleteSessionById($id)
{
    $connection = connectToDatabase();

    $query = "DELETE FROM online_users WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to insert a new store
function insertStore($storeName, $logo, $favicon, $color)
{
    $connection = connectToDatabase();

    $query1 = "SELECT * FROM stores WHERE store_name='$storeName'";
    if ($connection->query($query1)->num_rows > 0) {
        return array("success" => false, "data" => "Store name already exists.");
    } else {
        $query = "INSERT INTO stores (store_name, logo, favicon, color) VALUES ('$storeName', '$logo', '$favicon', '$color')";

        if ($connection->query($query) === TRUE) {
            return array("success" => true, "data" => "New record created successfully.");
        } else {
            return array("success" => false, "data" => "Error: " . $query . "<br>" . $connection->error);
        }
    }
}

// Function to delete a store by ID
function deleteStoreById($id)
{
    $connection = connectToDatabase();

    $query = "DELETE FROM stores WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to update a store
function updateStore($updateData, $conditions)
{
    $connection = connectToDatabase();

    $updateString = "";
    foreach ($updateData as $key => $value) {
        $updateString .= "$key='$value',";
    }
    $updateString = rtrim($updateString, ',');

    $conditionString = "";
    foreach ($conditions as $key => $value) {
        $conditionString .= "$key='$value' AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    $query = "UPDATE stores SET $updateString WHERE $conditionString";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record updated successfully.");
    } else {
        return array("success" => false, "data" => "Error updating record: " . $connection->error);
    }
}


// Function to get all stores
function getAllStores()
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM stores";
    $result = $connection->query($query);

    $stores = [];
    while ($row = $result->fetch_assoc()) {
        $stores[] = $row;
    }
    return array("success" => true, "data" => $stores);
}

// Function to get a store by ID
function getStoreById($id)
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM stores WHERE id=$id";
    $result = $connection->query($query);

    if ($result->num_rows == 1) {
        $store = $result->fetch_assoc();
        return array("success" => true, "data" => $store);
    } else {
        return array("success" => false, "data" => "Store not found.");
    }
}

function insertStoreItem($store, $itemName, $itemAmount, $itemID, $itemDescription, $defaultPrice, $salePrice, $category, $amountPurchased, $hotDeal, $image)
{
    $connection = connectToDatabase();

    // Sanitize inputs
    $store = sanitize($store);
    $itemName = sanitize($itemName);
    $itemDescription = sanitize($itemDescription);
    $category = sanitize($category);

    // Prepare query
    $query = "INSERT INTO store_items (store, item_name, item_amount, item_id, item_description, default_price, sale_price, category, amount_purchased, hot_deal, image) VALUES ('$store', '$itemName', $itemAmount, $itemID, '$itemDescription', $defaultPrice, $salePrice, '$category', $amountPurchased, $hotDeal, '$image')";

    // Execute query
    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "New record created successfully.");
    } else {
        return array("success" => false, "data" => "Error: " . $query . "<br>" . $connection->error);
    }
}
function deleteStoreItemById($id)
{
    $connection = connectToDatabase();

    // Prepare query
    $query = "DELETE FROM store_items WHERE id=$id";

    // Execute query
    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

function getAllStoreItems()
{
    $connection = connectToDatabase();

    // Prepare query
    $query = "SELECT * FROM store_items";

    // Execute query
    $result = $connection->query($query);

    // Fetch data
    $storeItems = [];
    while ($row = $result->fetch_assoc()) {
        $storeItems[] = $row;
    }

    return array("success" => true, "data" => $storeItems);
}

function getStoreItemById($id)
{
    $connection = connectToDatabase();

    // Prepare query
    $query = "SELECT * FROM store_items WHERE id=$id";

    // Execute query
    $result = $connection->query($query);

    // Fetch data
    if ($result->num_rows == 1) {
        $storeItem = $result->fetch_assoc();
        return array("success" => true, "data" => $storeItem);
    } else {
        return array("success" => false, "data" => "Store item not found.");
    }
}

function updateStoreItem($updateData, $conditions)
{
    $connection = connectToDatabase();

    // Prepare update string
    $updateString = "";
    foreach ($updateData as $key => $value) {
        $updateString .= "$key='$value',";
    }
    $updateString = rtrim($updateString, ',');

    // Prepare condition string
    $conditionString = "";
    foreach ($conditions as $key => $value) {
        $conditionString .= "$key='$value' AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    // Prepare query
    $query = "UPDATE store_items SET $updateString WHERE $conditionString";

    // Execute query
    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record updated successfully.");
    } else {
        return array("success" => false, "data" => "Error updating record: " . $connection->error);
    }
}

// Function to insert a new vote link
function insertVoteLink($title, $url, $siteId, $active)
{
    $connection = connectToDatabase();

    // Check if the vote link title already exists
    $existingVoteLink = $connection->query("SELECT * FROM vote_links WHERE title='$title'")->fetch_assoc();
    if ($existingVoteLink) {
        return array("success" => false, "data" => "Vote link with the same title already exists.");
    }

    $title = $connection->real_escape_string($title);
    $url = $connection->real_escape_string($url);
    $siteId = intval($siteId);
    $active = intval($active);

    $query = "INSERT INTO vote_links (title, url, site_id, active) VALUES ('$title', '$url', $siteId, $active)";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "New vote link inserted successfully.");
    } else {
        return array("success" => false, "data" => "Error inserting vote link: " . $query . "<br>" . $connection->error);
    }
}

// Function to delete a vote link by ID
function deleteVoteLinkById($id)
{
    $connection = connectToDatabase();

    $query = "DELETE FROM vote_links WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Vote link deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting vote link: " . $connection->error);
    }
}

// Function to update a vote link
function updateVoteLink($updateData, $conditions)
{
    $connection = connectToDatabase();

    $updateString = "";
    foreach ($updateData as $key => $value) {
        $updateString .= "$key='$value',";
    }
    $updateString = rtrim($updateString, ',');

    $conditionString = "";
    foreach ($conditions as $key => $value) {
        $conditionString .= "$key='$value' AND ";
    }
    $conditionString = rtrim($conditionString, 'AND ');

    $query = "UPDATE vote_links SET $updateString WHERE $conditionString";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Vote link updated successfully.");
    } else {
        return array("success" => false, "data" => "Error updating vote link: " . $connection->error);
    }
}

// Function to get all vote links
function getAllVoteLinks()
{
    $connection = connectToDatabase();

    $query = "SELECT * FROM vote_links";
    $result = $connection->query($query);

    $voteLinks = [];
    while ($row = $result->fetch_assoc()) {
        $voteLinks[] = $row;
    }
    return array("success" => true, "data" => $voteLinks);
}

// Function to get a vote link by ID
function getVoteLinkById($id)
{
    $connection = connectToDatabase();

    $id = intval($id);

    $query = "SELECT * FROM vote_links WHERE id=$id";
    $result = $connection->query($query);

    if ($result->num_rows == 1) {
        $voteLink = $result->fetch_assoc();
        return array("success" => true, "data" => $voteLink);
    } else {
        return array("success" => false, "data" => "Vote link not found.");
    }
}