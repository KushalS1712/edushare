<?php
include('db_connect.php');

if(isset($_POST['file_id']) && isset($_POST['rating'])){
    $fid = mysqli_real_escape_string($conn, $_POST['file_id']);
    $val = intval($_POST['rating']); // Ensures the rating is a number
    
    // Update the resources table using your specific column names
    $sql = "UPDATE resources SET 
            total_rating_score = total_rating_score + $val, 
            rating_count = rating_count + 1 
            WHERE id = '$fid'";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Thank you for your valueable rating!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error updating rating: " . mysqli_error($conn);
    }
}
?>