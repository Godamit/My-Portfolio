<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "786786";
$dbname = "file_uploads_db";
$response = [];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $response['error'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit;
}

$choice = $_POST['choice'];
// echo ($choice);
if($choice == 1){ 
if (isset($_FILES['file']) && pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) === 'csv') {
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['file_status'] = "Upload error: " . $file['error'];
        echo json_encode($response);
        exit;
    }
    // uniqid(string $prefix = "", bool $more_entropy = false): string;

    $filename = basename($file['name']). "_" . uniqid();
    $filetype = $file['type'];
    $filesize = $file['size'];
    $uploadDir = '/home/shivam/My-Portfolio/Portfolio/backend/uploads/';
    $filepath = $uploadDir . $filename;
    $filestatus = 1;

    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        $response['file_status'] = "Failed to move uploaded file.";
        echo json_encode($response);
        exit;
    }

    $response['file_status'] = "File uploaded to MySQL directory.";
    $response['filename'] = $filename;

    // Insert into uploads table
    try {
        $sql = "
            INSERT INTO uploads (filename, filetype, filesize, filepath, status)
            VALUES (
                '$filename',
                '$filetype',
                $filesize,
                '$filepath',
                $filestatus
            )
        ";
        $conn->query($sql);
        $response['insert_status'] = "success";
    } catch (Exception $e) {
        $response['insert_status'] = "Insert failed: " . $e->getMessage();
    }
}
}else if($choice == 2){

    $sql = "SELECT id, filename, status FROM uploads ORDER BY id DESC limit 5";
    $result = $conn->query($sql);

    $uploads = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $uploads[] = $row;
        }
        $response['uploads'] = $uploads;
    } else {
        $response['uploads'] = "File format mismatch";
    }

$conn->close();
}else{
    $response['choice'] = 'Invalid choice';

}
echo json_encode($response);
exit;
?>
