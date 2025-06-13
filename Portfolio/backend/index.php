<?php
header("Content-Type: application/json");

// Database connection variables
$servername = "localhost";
$username = "root";
$password = "786786";
$dbname = "file_uploads_db";

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

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['file_status'] = "File upload error code: " . $file['error'];
        echo json_encode($response);
        exit;
    }

    $fileContent = file_get_contents($file['tmp_name']);
    $filename = $file['name'];
    $filetype = $file['type'];
    $filesize = $file['size'];

    $destination = __DIR__ . "/uploads/filex.odt";
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $response['file_status'] = "File uploaded successfully!";
    } else {
        $response['file_status'] = "Failed to move uploaded file.";
    }

    try {
        $stmt = $conn->prepare("INSERT INTO uploads (filename, filetype, filesize, filedata) 
        VALUES (:filename, :filetype, :filesize, :filedata)");
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':filetype', $filetype);
        $stmt->bindParam(':filesize', $filesize);
        $stmt->bindParam(':filedata', $fileContent, PDO::PARAM_LOB);
        $stmt->execute();

        $response['db_insert'] = "File metadata and data inserted into database.";
    } catch (PDOException $e) {
        $response['db_insert'] = "DB insert failed: " . $e->getMessage();
    }
} else {
    $response['file_status'] = "No file uploaded";
}
// Output response as JSON
echo json_encode($response);
?>
