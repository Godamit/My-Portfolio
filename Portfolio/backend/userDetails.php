<?php

header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "786786";
$db_name = "Students";

$response = [];


try {
    // Create connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $response['db_status'] = "Connected successfully to database: $dbname";
} catch(PDOException $e) {
    $response['db_status'] = "Connection failed: " . $e->getMessage();
    echo json_encode($response);
    exit;
}

$student1 = $_POST['studentinfo'];
echo($student1);


?>