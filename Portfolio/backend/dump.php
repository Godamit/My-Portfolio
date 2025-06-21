<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "786786";
$dbname = "file_uploads_db";

$response = [];

// Connect using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $response['error'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit;
}

$sql2 = "SELECT status FROM statusmaster;";
$status = $conn->query($sql2);
if ($status && $row = $status->fetch_assoc()) {
if ($row['status'] === "idle") {
        // Mark as running
    $conn->query("UPDATE statusmaster SET status = 'running'");

    // Get the most recent file with status = 1
    $sql1 = "SELECT filename FROM uploads WHERE status = 1 ORDER BY id DESC LIMIT 5";
    echox($sql1);
    $result = $conn->query($sql1);

    if ($result && $result->num_rows > 0) {
        // $file = $result->fetch_assoc();
        while($file = $result->fetch_assoc()){ 
            $filename = $file['filename'];
            $filepath = '/home/shivam/My-Portfolio/Portfolio/backend/uploads/' . $filename;
            echox($filepath);
        // Now use a new PDO connection to execute LOAD DATA LOCAL INFILE (because MySQLi doesn't support LOCAL INFILE well)
        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, [
                PDO::MYSQL_ATTR_LOCAL_INFILE => true
            ]);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "
                LOAD DATA LOCAL INFILE '$filepath'
                INTO TABLE users
                FIELDS TERMINATED BY ',' 
                ENCLOSED BY '\"' 
                LINES TERMINATED BY '\n'
                IGNORE 1 ROWS
                (id, name, surname, email, username, password, created_at);
            ";
            
            $pdo->exec($sql);

            // Update status in uploads table via MySQLi
            $conn->query("UPDATE uploads SET status = 2 WHERE filename = '$filename'");
            
            echox("CSV data imported into 'users' table.");
            echox("Upload status updated to 2.");
        } catch (PDOException $e) {
            echox("CSV import failed: " . $e->getMessage());
        }
    }
    $conn->query("UPDATE statusmaster SET status = 'idle'");
    } else {
        echox("No pending file found (status = 1).");
    $conn->query("UPDATE statusmaster SET status = 'idle'");

    }

}else{
    echox("Script already running");
}
}


function echox ($msg){
    echo date('Y-m-d::H:i:s').": $msg\n";
}

?>




