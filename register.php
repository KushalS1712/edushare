<?php
include('db_connect.php');

if(isset($_POST['register'])){
    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // INSERT query matches your table columns: name, email, password, role
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$pass', 'student')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration Successful! Please login.'); window.location='index.php';</script>";
    } else {
        echo "<div class='text-red-500 text-center'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>EduShare - Register</title>
</head>
<body class="bg-slate-50 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-96 border border-slate-100">
        <div class="text-center mb-6">
             <h2 class="text-2xl font-bold text-blue-700">CREATE ACCOUNT</h2>
             <p class="text-slate-400 text-sm">Join the EduShare Community</p>
        </div>
        
        <form action="register.php" method="POST" class="space-y-4">
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Full Name</label>
                <input type="text" name="fullname" placeholder="Enter Full Name" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Email Address</label>
                <input type="email" name="email" placeholder="name123*#$@gmail.com" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Password</label>
                <input type="password" name="password" placeholder="Create Password" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <button type="submit" name="register" class="w-full bg-blue-600 text-white p-3 rounded-lg font-bold hover:bg-blue-700 transition">
                Register
            </button>
        </form>
        
        <p class="mt-6 text-center text-sm text-slate-500">
            Already have an account? <a href="index.php" class="text-blue-600 font-bold hover:underline">Login</a>
        </p> 
    </div>
</body>
</html>