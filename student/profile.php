<?php
// Initialize database connection
if (file_exists('../config/db.php')) {
    require_once '../config/db.php';
} elseif (file_exists('../Config/db.php')) {
    require_once '../Config/db.php';
}

// Restrict access to authenticated students only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$status_msg = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name   = trim($_POST['full_name']);
    $mobile      = trim($_POST['mobile']);
    $college     = trim($_POST['college']);
    $career_goal = trim($_POST['career_goal']);

    // Update users table
    $u_stmt = $pdo->prepare("UPDATE users SET full_name = ?, mobile = ? WHERE id = ?");
    $u_stmt->execute([$full_name, $mobile, $user_id]);

    // Check if student profile row exists
    $chk = $pdo->prepare("SELECT id FROM student_profiles WHERE user_id = ?");
    $chk->execute([$user_id]);

    if ($chk->rowCount() > 0) {
        $p_stmt = $pdo->prepare("UPDATE student_profiles SET college = ?, career_goal = ? WHERE user_id = ?");
        $p_stmt->execute([$college, $career_goal, $user_id]);
    } else {
        $p_stmt = $pdo->prepare("INSERT INTO student_profiles (user_id, college, career_goal) VALUES (?, ?, ?)");
        $p_stmt->execute([$user_id, $college, $career_goal]);
    }

    $status_msg = "Profile updated successfully!";
}

// Fetch current details without nonexistent columns
$stmt = $pdo->prepare("
    SELECT u.full_name, u.email, u.mobile, sp.college, sp.career_goal 
    FROM users u 
    LEFT JOIN student_profiles sp ON u.id = sp.user_id 
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | SkillSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-slate-200 min-h-screen flex flex-col selection:bg-cyan-500 selection:text-slate-950">

    <!-- Header Navigation -->
    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="dashboard.php" class="flex items-center gap-2 text-slate-300 hover:text-white transition text-xs font-semibold">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-cyan-500 flex items-center justify-center text-slate-950 font-black text-sm">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="text-sm font-bold text-white tracking-wide">Skill<span class="text-cyan-400">Sync</span></span>
            </div>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-6 py-10 flex-1 w-full space-y-6">

        <?php if ($status_msg): ?>
            <div class="bg-emerald-500/10 border border-emerald-500/40 text-emerald-300 text-xs p-4 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span><?php echo $status_msg; ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-6 sm:p-8 space-y-6">
            <div>
                <h1 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-user-pen text-cyan-400"></i> Manage Career Profile
                </h1>
                <p class="text-xs text-slate-400 mt-1">Keep your personal details and academic goals updated.</p>
            </div>

            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">Full Name</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user_data['full_name'] ?? ''); ?>" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-xs text-white focus:border-cyan-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">Email (Read Only)</label>
                        <input type="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" disabled class="w-full bg-slate-950/60 border border-slate-800/80 rounded-lg px-4 py-2.5 text-xs text-slate-500 cursor-not-allowed">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">Mobile</label>
                        <input type="text" name="mobile" value="<?php echo htmlspecialchars($user_data['mobile'] ?? ''); ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-xs text-white focus:border-cyan-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">College / Institute</label>
                        <input type="text" name="college" value="<?php echo htmlspecialchars($user_data['college'] ?? ''); ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-xs text-white focus:border-cyan-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">Target Career Goal</label>
                    <input type="text" name="career_goal" placeholder="e.g. Full-Stack Developer, Data Analyst" value="<?php echo htmlspecialchars($user_data['career_goal'] ?? ''); ?>" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-xs text-white focus:border-cyan-500 focus:outline-none">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold py-3 rounded-xl text-xs transition shadow-lg shadow-cyan-500/25">
                        Save Profile Changes
                    </button>
                </div>
            </form>
        </div>

    </main>
</body>
</html>