    <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
if (file_exists('../config/db.php')) {
    require_once '../config/db.php';
} elseif (file_exists('../Config/db.php')) {
    require_once '../Config/db.php';
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Profile Information
$stmt = $pdo->prepare("SELECT u.full_name, sp.college, sp.career_goal FROM users u LEFT JOIN student_profiles sp ON u.id = sp.user_id WHERE u.id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch();

// Assessment Metrics & Check if Certificate is eligible
$assessment_stmt = $pdo->prepare("SELECT COUNT(*) AS total_taken, AVG(score) AS avg_score, MAX(score) AS max_score FROM assessment_results WHERE user_id = ?");
$assessment_stmt->execute([$user_id]);
$assessment_stats = $assessment_stmt->fetch();

$has_completed_assessment = !empty($assessment_stats['total_taken']) && $assessment_stats['total_taken'] > 0;

// Fetch Student Applications
$my_apps_stmt = $pdo->prepare("
    SELECT ja.id, ja.status, ja.applied_at, j.title, j.company_name, j.location, j.stipend_salary
    FROM job_applications ja 
    LEFT JOIN jobs j ON ja.job_id = j.id 
    WHERE ja.student_id = ?
    ORDER BY ja.id DESC
");
$my_apps_stmt->execute([$user_id]);
$my_applications = $my_apps_stmt->fetchAll(PDO::FETCH_ASSOC);

// Available Internships
$jobs_stmt = $pdo->query("SELECT * FROM jobs ORDER BY id DESC LIMIT 10");
$recent_jobs = $jobs_stmt->fetchAll();

// Applied IDs Array
$applied_ids_stmt = $pdo->prepare("SELECT job_id FROM job_applications WHERE student_id = ?");
$applied_ids_stmt->execute([$user_id]);
$applied_ids = $applied_ids_stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | SkillSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-slate-200 min-h-screen flex flex-col selection:bg-cyan-500 selection:text-slate-950">

    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-500 flex items-center justify-center text-slate-950 font-black shadow-lg shadow-cyan-500/20">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="text-lg font-bold text-white tracking-wide">Skill<span class="text-cyan-400">Sync</span></span>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-300 font-medium"><?php echo htmlspecialchars($profile['full_name'] ?? 'Student'); ?></span>
                <a href="profile.php" class="text-xs bg-slate-800 hover:bg-slate-700 text-cyan-400 border border-slate-700 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa-solid fa-user-pen"></i> Edit Profile
                </a>
                <a href="../auth/logout.php" class="text-xs bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 px-3 py-1.5 rounded-lg transition">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8 flex-1 w-full space-y-8">

        <!-- Welcome Banner with Certificate Action Button -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-cyan-950/50 border border-slate-800 rounded-2xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-xl">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Welcome back, <?php echo htmlspecialchars($profile['full_name'] ?? ''); ?>!</h1>
                <p class="text-xs text-slate-400 mt-1">
                    College: <span class="text-slate-200"><?php echo htmlspecialchars($profile['college'] ?? 'Not set'); ?></span> | 
                    Goal: <span class="text-cyan-400 font-medium"><?php echo htmlspecialchars($profile['career_goal'] ?? 'Aspiring Tech Professional'); ?></span>
                </p>
            </div>
            
            <div class="flex items-center gap-3 flex-wrap">
                <?php if ($has_completed_assessment): ?>
                    <a href="certificate.php" class="bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-300 border border-emerald-500/40 font-bold px-4 py-2.5 rounded-xl text-xs transition flex items-center gap-2 shadow-lg shadow-emerald-500/10">
                        <i class="fa-solid fa-award text-sm text-emerald-400"></i> View Certificate
                    </a>
                <?php endif; ?>

                <a href="assessments.php" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-4 py-2.5 rounded-xl text-xs transition shadow-lg shadow-cyan-500/20 flex items-center gap-2">
                    <i class="fa-solid fa-pen-nib"></i> Take Skill Assessment
                </a>
            </div>
        </div>

        <!-- Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-xl flex items-center justify-between">
                <div>
                    <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Assessments Completed</div>
                    <div class="text-3xl font-extrabold text-white mt-2"><?php echo $assessment_stats['total_taken'] ?? 0; ?></div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-xl flex items-center justify-between">
                <div>
                    <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Average Score</div>
                    <div class="text-3xl font-extrabold text-cyan-400 mt-2">
                        <?php echo $assessment_stats['avg_score'] ? round($assessment_stats['avg_score'], 1) . '%' : 'N/A'; ?>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>

            <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-xl flex items-center justify-between">
                <div>
                    <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Applied Internships</div>
                    <div class="text-3xl font-extrabold text-emerald-400 mt-2"><?php echo count($my_applications); ?></div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
            </div>
        </div>

        <!-- My Submitted Applications Section -->
        <div class="space-y-4">
            <h2 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-cyan-400"></i> My Submitted Applications
            </h2>

            <div class="bg-slate-900/60 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="bg-slate-950 text-slate-400 uppercase font-semibold border-b border-slate-800">
                        <tr>
                            <th class="p-4">Role Applied</th>
                            <th class="p-4">Company</th>
                            <th class="p-4">Applied Date</th>
                            <th class="p-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        <?php if (!empty($my_applications)): ?>
                            <?php foreach ($my_applications as $app): ?>
                                <tr class="hover:bg-slate-800/25 transition">
                                    <td class="p-4 font-semibold text-white">
                                        <?php echo htmlspecialchars($app['title'] ?? 'Internship'); ?>
                                    </td>
                                    <td class="p-4 text-slate-400">
                                        <?php echo htmlspecialchars($app['company_name'] ?? 'N/A'); ?>
                                    </td>
                                    <td class="p-4 text-slate-500">
                                        <?php echo !empty($app['applied_at']) ? date('d M Y', strtotime($app['applied_at'])) : 'Recent'; ?>
                                    </td>
                                    <td class="p-4 text-right">
                                        <?php 
                                        $cur_status = $app['status'] ?? 'Applied';
                                        $badge = match($cur_status) {
                                            'Shortlisted' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                            'Rejected'    => 'bg-red-500/15 text-red-400 border-red-500/30',
                                            default       => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30',
                                        };
                                        ?>
                                        <span class="<?php echo $badge; ?> border px-3 py-1 rounded-full text-xs font-bold inline-block">
                                            <?php echo htmlspecialchars($cur_status); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-6 text-center text-slate-500">
                                    You have not applied for any internships yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recommended Internships -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-cyan-400"></i> Recommended Internships
                </h2>
                <span class="text-xs text-slate-400 font-medium">Curated verified opportunities</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($recent_jobs as $job): ?>
                    <?php $has_applied = in_array($job['id'], $applied_ids); ?>
                    <div class="bg-slate-900/40 border border-slate-800 p-5 rounded-2xl space-y-3 hover:border-slate-700/80 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-white text-base"><?php echo htmlspecialchars($job['title']); ?></h3>
                                <p class="text-xs text-slate-400"><?php echo htmlspecialchars($job['company_name']); ?> • <?php echo htmlspecialchars($job['location']); ?></p>
                            </div>
                            <span class="text-xs bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 px-2.5 py-1 rounded-md font-semibold">
                                Internship
                            </span>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed"><?php echo htmlspecialchars($job['description']); ?></p>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-800/80">
                            <span class="text-xs font-semibold text-emerald-400"><?php echo htmlspecialchars($job['stipend_salary']); ?></span>
                            <?php if ($has_applied): ?>
                                <span class="text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1">
                                    <i class="fa-solid fa-check"></i> Applied
                                </span>
                            <?php else: ?>
                                <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="text-xs bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-4 py-1.5 rounded-lg transition shadow-md shadow-cyan-500/10">
                                    Apply Now
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </main>
</body>
</html>