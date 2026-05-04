<?php 
session_start();
include('db_connect.php');
// Security check: only allow admins
if(!isset($_SESSION['user_id'])) header("Location: login.php");
$uid = $_SESSION['user_id'];
$check = mysqli_query($conn, "SELECT role FROM users WHERE id='$uid'");
$user = mysqli_fetch_assoc($check);
if($user['role'] !== 'admin') die("Access Denied: Admins Only");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>EduShare Admin</title>
</head>
<body class="bg-slate-100">
    <div class="max-w-6xl mx-auto p-8">
        <h1 class="text-3xl font-bold text-slate-800 mb-8">Admin Control Panel</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Manage Files -->
            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Manage All Resources</h2>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-400 border-b">
                            <th class="py-2">Title</th>
                            <th>Uploader</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($conn, "SELECT resources.*, users.username FROM resources JOIN users ON resources.uploaded_by = users.id");
                        while($row = mysqli_fetch_assoc($res)) {
                            echo "<tr class='border-b'>
                                <td class='py-3'>{$row['title']}</td>
                                <td>{$row['username']}</td>
                                <td><a href='delete.php?id={$row['id']}' class='text-red-500 hover:underline'>Delete</a></td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Manage Users -->
            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Platform Users</h2>
                <?php
                $users = mysqli_query($conn, "SELECT * FROM users");
                while($u = mysqli_fetch_assoc($users)) {
                    echo "<div class='flex justify-between py-2 border-b'>
                        <span>{$u['fullname']} ({$u['username']})</span>
                        <span class='text-xs font-bold uppercase'>{$u['role']}</span>
                    </div>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>