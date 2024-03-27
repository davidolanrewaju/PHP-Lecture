<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'practice_db';

//Create database connection
$connection = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}