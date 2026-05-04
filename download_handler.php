<?php
include('db_connect.php');

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // 1. Fetch file path from your column 'file_path'
    $res = mysqli_query($conn, "SELECT file_path FROM resources WHERE id = '$id'");
    $data = mysqli_fetch_assoc($res);
    
    if($data && file_exists($data['file_path'])) {
        // 2. Increment download total
        mysqli_query($conn, "UPDATE resources SET downloads = downloads + 1 WHERE id = '$id'");
        
        // 3. Force browser to download the file
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . basename($data['file_path']));
        readfile($data['file_path']);
        exit();
    } else {
        echo "<script>alert('Error: File not found.'); window.location='dashboard.php';</script>";
    }
}
?>