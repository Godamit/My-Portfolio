<?php
try {
        $loadQuery = "
    LOAD DATA LOCAL INFILE '" . $destination . "'
    INTO TABLE users
    FIELDS TERMINATED BY ',' 
    ENCLOSED BY '\"'
    LINES TERMINATED BY '\n'
    IGNORE 1 ROWS
    (@dummy, first_name, last_name, email, username, password, created_at)
    ";



    $response['csv_upload'] = "CSV data inserted into 'users' table.";
    } catch (PDOException $e) {
        $response['error'] = "LOAD DATA error: " . $e->getMessage();
    }
} else {
    $response['file_status'] = "No file uploaded.";
}

echo json_encode($response);

?>