<?php
$host = 'localhost';     
$dbname = 'ycd'; 
$username = 'root'; 
$password = 'ugc@now111Z'; 

// Create connection
$conn = new mysqli($host, $username, $password, $dbname,3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>