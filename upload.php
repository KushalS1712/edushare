<?php
session_start();
include('db_connect.php');

if(isset($_POST['submit'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $uploader_id = $_SESSION['user_id'];

    // This must match name="resource" from your dashboard form
    $file_name = $_FILES['resource']['name'];
    $file_tmp = $_FILES['resource']['tmp_name'];
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Rename file slightly to avoid overwriting existing files
    $target_file = $upload_dir . time() . "_" . $file_name;

    if(move_uploaded_file($file_tmp, $target_file)){
        // RECTIFIED: Matches your columns: title, subject, file_path, uploader_id
        $sql = "INSERT INTO resources (title, subject, file_path, uploader_id, downloads) 
                VALUES ('$title', '$subject', '$target_file', '$uploader_id', 0)";
        
        if(mysqli_query($conn, $sql)){
            echo "<script>alert('File Shared Successfully!'); window.location='dashboard.php';</script>";
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "Failed to upload file to the folder. Check if 'uploads' folder exists.";
    }
}
?>