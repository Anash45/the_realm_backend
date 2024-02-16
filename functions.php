<?php

// Function to establish a database connection
function connectToDatabase() {
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

function sanitize($input) {
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
function insertAccount($username, $password, $role) {
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
function deleteAccountById($id) {
    $connection = connectToDatabase();

    $query = "DELETE FROM accounts WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to update an account
function updateAccount($updateData, $conditions) {
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
function getAllAccounts() {
    $connection = connectToDatabase();

    $query = "SELECT * FROM accounts";
    $result = $connection->query($query);

    $accounts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $accounts[] = $row;
        }
        return array("success" => true, "data" => $accounts);
    } else {
        return array("success" => false, "data" => "No accounts found.");
    }
}

// Function to get an account by ID
function getAccountById($id) {
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
function getAccountByUsername($username) {
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
function insertVote($serverName, $username, $toplist, $ipAddress, $rewardAmount, $timeVoted, $claimed, $vpn) {
    $connection = connectToDatabase();

    $query = "INSERT INTO finalized_votes (server_name, username, toplist, ip_address, reward_amount, time_voted, claimed, vpn) VALUES ('$serverName', '$username', '$toplist', '$ipAddress', $rewardAmount, $timeVoted, $claimed, $vpn)";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "New record created successfully.");
    } else {
        return array("success" => false, "data" => "Error: " . $query . "<br>" . $connection->error);
    }
}

// Function to delete a finalized vote by ID
function deleteVoteById($id) {
    $connection = connectToDatabase();

    $query = "DELETE FROM finalized_votes WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to update a finalized vote
function updateVote($updateData, $conditions) {
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
function getAllVotes() {
    $connection = connectToDatabase();

    $query = "SELECT * FROM finalized_votes";
    $result = $connection->query($query);

    $votes = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $votes[] = $row;
        }
        return array("success" => true, "data" => $votes);
    } else {
        return array("success" => false, "data" => "No votes found.");
    }
}

// Function to get a finalized vote by ID
function getVoteById($id) {
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
function getVotesByServerName($serverName) {
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
function insertStoreCategory($store, $categoryName, $categoryImage) {
    $connection = connectToDatabase();

    $query = "INSERT INTO store_categories (store, category_name, category_image) VALUES ('$store', '$categoryName', '$categoryImage')";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "New record created successfully.");
    } else {
        return array("success" => false, "data" => "Error: " . $query . "<br>" . $connection->error);
    }
}

// Function to delete a store category by ID
function deleteStoreCategoryById($id) {
    $connection = connectToDatabase();

    $query = "DELETE FROM store_categories WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to update a store category
function updateStoreCategory($updateData, $conditions) {
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
function getAllStoreCategories() {
    $connection = connectToDatabase();

    $query = "SELECT * FROM store_categories";
    $result = $connection->query($query);

    $storeCategories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $storeCategories[] = $row;
        }
        return array("success" => true, "data" => $storeCategories);
    } else {
        return array("success" => false, "data" => "No store categories found.");
    }
}

// Function to get a store category by ID
function getStoreCategoryById($id) {
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
function getStoreCategoriesByStore($store) {
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
function insertDonation($donorName, $amount, $date, $ipAddress) {
    $connection = connectToDatabase();

    $query = "INSERT INTO donations (donor_name, amount, date, ip_address) VALUES ('$donorName', $amount, '$date', '$ipAddress')";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "New record created successfully.");
    } else {
        return array("success" => false, "data" => "Error: " . $query . "<br>" . $connection->error);
    }
}

// Function to delete a donation by ID
function deleteDonationById($id) {
    $connection = connectToDatabase();

    $query = "DELETE FROM donations WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}

// Function to update a donation
function updateDonation($updateData, $conditions) {
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
function getAllDonations() {
    $connection = connectToDatabase();

    $query = "SELECT * FROM donations";
    $result = $connection->query($query);

    $donations = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $donations[] = $row;
        }
        return array("success" => true, "data" => $donations);
    } else {
        return array("success" => false, "data" => "No donations found.");
    }
}

// Function to get a donation by ID
function getDonationById($id) {
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

function getAllActiveSessions() {
    $connection = connectToDatabase();

    $query = "SELECT online_users.id, accounts.username, online_users.last_activity FROM online_users JOIN accounts ON online_users.account_id = accounts.id";
    $result = $connection->query($query);

    $sessions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        return array("success" => true, "data" => $sessions);
    } else {
        return array("success" => false, "data" => "Sessions not found.");
    }
}
function deleteSessionById($id) {
    $connection = connectToDatabase();

    $query = "DELETE FROM online_users WHERE id=$id";

    if ($connection->query($query) === TRUE) {
        return array("success" => true, "data" => "Record deleted successfully.");
    } else {
        return array("success" => false, "data" => "Error deleting record: " . $connection->error);
    }
}