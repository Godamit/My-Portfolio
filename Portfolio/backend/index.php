<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "786786";
$dbname = "file_uploads_db";

$response = [];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, [
        PDO::MYSQL_ATTR_LOCAL_INFILE => true
    ]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $response['db_status'] = "Connected to $dbname";
} catch(PDOException $e) {
    $response['db_status'] = "Connection failed: " . $e->getMessage();
    echo json_encode($response);
    exit;
}

if (isset($_FILES['file']) && pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) === 'csv') {
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['file_status'] = "Upload error: " . $file['error'];
        echo json_encode($response);
        exit;
    }

    $uploadPath = '/home/shivam/My-Portfolio/Portfolio/backend/uploads/' . basename($file['name']);

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $response['file_status'] = "Failed to move uploaded file to LOAD DATA path.";
        echo json_encode($response);
        exit;
    }

    // insert into uploads table file data

    //     done

    // seperate process : 

    //     uploads table status = pending pick 
    //         file path dump in database 
    //         forloop for all 
    //         status = done


    echo $file['name'] ."\n";
    $response['file_status'] = "File uploaded to MySQL directory.";

    try {
        $conn->exec("LOAD DATA LOCAL INFILE '$uploadPath'
                     INTO TABLE users
                     FIELDS TERMINATED BY ',' 
                     ENCLOSED BY '\"'
                     LINES TERMINATED BY '\n'
                     IGNORE 1 ROWS");
        $response['csv_insert'] = "CSV data imported into 'users' table.";
    } catch (PDOException $e) {
        $response['csv_insert'] = "Failed CSV import: " . $e->getMessage();
    }

}

echo json_encode($response);
?>
