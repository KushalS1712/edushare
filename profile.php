<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$uid = $_SESSION['user_id'];
// Fetch user details
$u_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$uid'");
$user = mysqli_fetch_assoc($u_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>My Profile | EduShare</title>
</head>

<body class="bg-slate-50">
    <nav class="bg-white border-b p-4 shadow-sm">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="dashboard.php" class="text-blue-600 font-bold">← Back to Dashboard</a>
            <h1 class="font-bold">My Profile</h1>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto mt-10 p-6">
        <!-- Profile Header -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border mb-8 flex items-center gap-6">
            <div class="h-20 w-20 bg-blue-100 rounded-full flex items-center justify-center text-3xl">👤</div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800"><?php echo htmlspecialchars($user['name']); ?></h2>
                <p class="text-slate-500"><?php echo htmlspecialchars($user['email']); ?></p>
                <span class="text-[10px] bg-blue-600 text-white px-2 py-1 rounded-full uppercase font-bold"><?php echo $user['role']; ?></span>
            </div>
        </div>
        <h3 class="text-xl font-bold mb-4">My Uploaded Files</h3>
        <div class="grid gap-4">
            <?php
            // Fetch only files uploaded by THIS user, including rating data
            $my_files = mysqli_query($conn, "SELECT * FROM resources WHERE uploader_id = '$uid' ORDER BY id DESC");

            if (mysqli_num_rows($my_files) > 0) {
                while ($row = mysqli_fetch_assoc($my_files)) {
                    // Calculate Average Rating
                    $avg_rating = 0;
                    if ($row['rating_count'] > 0) {
                        $avg_rating = round($row['total_rating_score'] / $row['rating_count'], 1);
                    }
            ?>
                    <div class="bg-white p-4 rounded-xl border flex justify-between items-center shadow-sm">
                        <div>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded uppercase">
                                <?php echo htmlspecialchars($row['subject']); ?>
                            </span>
                            <h3 class="font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($row['title']); ?></h3>

                            <div class="flex items-center gap-3 mt-2">
                                <!-- Displaying the Rating -->
                                <div class="flex items-center text-amber-500 font-bold text-xs">
                                    ⭐ <?php echo $avg_rating; ?>
                                    <span class="text-slate-400 font-normal ml-1">(<?php echo $row['rating_count']; ?> votes)</span>
                                </div>
                                <div class="text-slate-400 text-xs">
                                    Downloads: <?php echo $row['downloads']; ?>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <!-- Link to view or download the file -->
                            <a href="<?php echo htmlspecialchars($row['file_path']); ?>"
                                class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-200"
                                download>Download</a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<div class='p-10 bg-white rounded-2xl border-2 border-dashed text-center text-slate-400 italic'>
                You haven't uploaded any academic resources yet.
              </div>";
            }
            ?>
        </div>

    </div>
</body>

</html>