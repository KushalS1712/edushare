<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$display_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Student';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Dashboard | EduShare</title>
</head>

<body class="bg-slate-50 min-h-screen">
    <nav class="bg-white border-b border-slate-200 p-4 sticky top-0 z-50 shadow-sm">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="text-2xl">📚</span>
                <h1 class="text-xl font-bold text-blue-700">EduShare</h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right border-r pr-4 border-slate-200">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Logged in as</p>
                    <p class="text-sm font-bold text-slate-800"><?php echo htmlspecialchars($display_name); ?></p>
                </div>
                <a href="profile.php" class="text-blue-600 font-bold">My Profile</a>
                <a href="logout.php" class="text-xs font-bold text-red-600 hover:bg-red-50 p-2 rounded-lg transition">LOGOUT</a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <aside>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-lg font-bold mb-4 text-slate-800 flex items-center gap-2">
                    <span>📤</span> Upload Notes
                </h2>
                <!-- ACTION: upload.php | METHOD: POST | ENCTYPE: multipart -->

                <form action="upload.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase">Title</label>
                        <input type="text" name="title" placeholder="e.g. Java Notes" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase">Subject</label>
                        <select name="subject" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm">
                            <option>Computer Science</option>
                            <option>Mathematics</option>
                            <option>Management</option>
                            <option>English</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-slate-400 uppercase">File (PDF)</label>
                        <input type="file" name="resource" accept=".pdf" class="block w-full text-xs text-slate-500 mt-1" required>
                    </div>

                    <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                        Share with Class
                    </button>
                </form>
            </div>
        </aside>

        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-xl font-bold text-slate-800">Study Materials Feed</h2>
            <?php
            // RECTIFIED QUERY: Matches uploader_id and name
            $sql = "SELECT resources.*, users.name as uploader_name 
                    FROM resources 
                    JOIN users ON resources.uploader_id = users.id 
                    ORDER BY resources.id DESC";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="bg-white p-5 rounded-2xl border shadow-sm flex justify-between items-center mb-4">
                        <div>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded uppercase"><?php echo htmlspecialchars($row['subject']); ?></span>
                            <h3 class="font-bold text-slate-800 mt-2"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="text-[11px] text-slate-400">By <?php echo htmlspecialchars($row['uploader_name']); ?></p>
                            <!-- Rating Form Added Here -->
                            <form action="rate.php" method="POST" class="mt-3 flex items-center gap-2">
                                <input type="hidden" name="file_id" value="<?php echo $row['id']; ?>">
                                <select name="rating" class="text-[10px] border border-slate-200 rounded bg-white p-1 outline-none focus:ring-1 focus:ring-blue-400">
                                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                    <option value="4">⭐⭐⭐⭐ (4)</option>
                                    <option value="3">⭐⭐⭐ (3)</option>
                                    <option value="2">⭐⭐ (2)</option>
                                    <option value="1">⭐ (1)</option>
                                </select>
                                <button type="submit" class="text-[10px] bg-blue-50 text-blue-700 px-2 py-1 rounded font-bold hover:bg-blue-100 transition">
                                    Rate
                                </button>
                            </form>
                        </div>
                        <a href="download_handler.php?id=<?php echo $row['id']; ?>" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-bold">Download</a>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-slate-400 italic text-center p-10'>No files shared yet.</p>";
            }
            ?>
        </div>
    </main>
</body>

</html>