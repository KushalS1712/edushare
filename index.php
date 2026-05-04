<?php
session_start();
include('db_connect.php');

if (isset($_POST['login'])) {
    // Sanitize input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password'];

    // BASED ON YOUR DB SCREENSHOT: Using 'email' column instead of 'username'
    $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $row = mysqli_fetch_assoc($res);

    if ($row && password_verify($pass, $row['password'])) {
        // Set sessions based on your actual table columns
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['name']; // Using 'name' column for the display name

        header("Location: dashboard.php");
        exit();
    } else {
        $error_msg = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>EduShare - Login</title>
</head>

<body class="bg-slate-50 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-96 border border-slate-100">
        <div class="text-center mb-6">
            <span class="text-4x1">📚</span>
            <h1 class="text-2xl font-bold text-slate-800 mt-2">EduShare Login</h1>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Share your resources with me...! </p>
        </div>

        <?php if (isset($error_msg)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-xs font-bold mb-4 border border-red-100 text-center">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <form action="index.php" method="POST" class="space-y-4">
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Email Address</label>
                <input type="email" name="email" placeholder="student@koshys.com" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Password</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
            </div>
            <button type="submit" name="login" class="w-full bg-blue-600 text-white p-3 rounded-xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition active:scale-95">
                Login
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-slate-500">
            Don't have an account? <a href="register.php" class="text-blue-600 font-bold hover:underline">Register here</a>
        </div>
    </div>
</body>

</html>