<?php
function connectToDatabase()
{
    $host = 'localhost';
    $username = 'root';
    $password = 'root';
    $database = 'the_realm_db';

    
    // $host = 'localhost';
    // $username = 'u956940883_the_realm';
    // $password = '8M&#&b~l~';
    // $database = 'u956940883_the_realm';

    $connection = new mysqli($host, $username, $password, $database);

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}