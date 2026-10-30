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

// Verify login status
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Redirect students away from corporate portal
if (isset($_SESSION['role']) && strtolower(trim($_SESSION['role'])) === 'student') {
    header("Location: ../student/dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// Handle application status change (Shortlist / Reject)
if (isset($_GET['action']) && isset($_GET['app_id'])) {
    $app_id = (int)$_GET['app_id'];
    $new_status = ($_GET['action'] === 'shortlist') ? 'Shortlisted' : (($_GET['action'] === 'reject') ? 'Rejected' : '');

    if (!empty($new_status) && $app_id > 0) {
        $update_stmt = $pdo->prepare("UPDATE job_applications SET status = ? WHERE id = ?");
        $update_stmt->execute([$new_status, $app_id]);
        $message = "Application status updated to " . htmlspecialchars($new_status) . " successfully!";
    }
}

// Handle posting a new internship
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_job'])) {
    $company_name   = trim($_POST['company_name'] ?? '');
    $title          = trim($_POST['title'] ?? '');
    $location       = trim($_POST['location'] ?? '');
    $stipend_salary = trim($_POST['stipend_salary'] ?? '');
    $description    = trim($_POST['description'] ?? '');

    if (!empty($company_name) && !empty($title)) {
        $insert = $pdo->prepare("INSERT INTO jobs (company_name, title, description, location, stipend_salary, job_type) VALUES (?, ?, ?, ?, ?, 'Internship')");
        $insert->execute([$company_name, $title, $description, $location, $stipend_salary]);
        $message = "New internship opening published successfully!";
    }
}

// Fetch all applications (फक्त खात्रीशीर कॉलम्स वापरले आहेत)
$app_stmt = $pdo->query("
    SELECT ja.id, ja.status, ja.applied_at, u.full_name, u.email, sp.college, sp.career_goal, j.title AS job_title, j.company_name,
    COALESCE((SELECT score FROM assessment_results WHERE user_id = u.id ORDER BY id DESC LIMIT 1), 100) AS candidate_score
    FROM job_applications ja
    JOIN users u ON ja.student_id = u.id
    LEFT JOIN student_profiles sp ON u.id = sp.user_id
    JOIN jobs j ON ja.job_id = j.id
    ORDER BY ja.id DESC
");
$applications = $app_stmt->fetchAll(PDO::FETCH_ASSOC);

$total_jobs = $pdo->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Portal | SkillSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-slate-200 min-h-screen flex flex-col selection:bg-cyan-500 selection:text-slate-950">

    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-cyan-500 flex items-center justify-center text-slate-950 font-black shadow-lg shadow-cyan-500/20">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <span class="text-base font-bold text-white tracking-wide">Skill<span class="text-cyan-400">Sync</span> <span class="text-xs text-slate-400 font-normal">| Corporate Recruiter Suite</span></span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs text-slate-300 font-semibold bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700">
                    <i class="fa-solid fa-building text-cyan-400 mr-1.5"></i><?php echo htmlspecialchars($_SESSION['name'] ?? 'Recruiter'); ?>
                </span>
                <a href="../auth/logout.php" class="text-xs bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 px-3 py-1.5 rounded-lg transition">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8 flex-1 w-full space-y-8">

        <?php if (!empty($message)): ?>
            <div class="bg-emerald-500/15 border border-emerald-500/40 text-emerald-300 text-xs p-4 rounded-xl flex items-center gap-3 shadow-lg shadow-emerald-500/10">
                <i class="fa-solid fa-circle-check text-base text-emerald-400"></i>
                <span class="font-medium"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Active Open Roles</div>
                    <div class="text-3xl font-extrabold text-white mt-1"><?php echo $total_jobs; ?></div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
            <div class="bg-slate-900/60 border border-slate-800 p-5 rounded-2xl flex items-center justify-between">
                <div>
                    <div class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Received Applications</div>
                    <div class="text-3xl font-extrabold text-cyan-400 mt-1"><?php echo count($applications); ?></div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Post Internship -->
            <div class="bg-slate-900/50 border border-slate-800 p-6 rounded-2xl h-fit space-y-4 shadow-xl">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-cyan-400"></i> Post New Internship
                </h2>
                <form method="POST" class="space-y-3.5">
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1 font-semibold uppercase tracking-wider">Company Name</label>
                        <input type="text" name="company_name" required placeholder="e.g. Google, TCS, Startup" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:border-cyan-500 focus:outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1 font-semibold uppercase tracking-wider">Internship Title</label>
                        <input type="text" name="title" required placeholder="Full-Stack Engineer Intern" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:border-cyan-500 focus:outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] text-slate-400 mb-1 font-semibold uppercase tracking-wider">Location</label>
                            <input type="text" name="location" placeholder="Pune / Remote" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-white focus:border-cyan-500 focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-400 mb-1 font-semibold uppercase tracking-wider">Stipend</label>
                            <input type="text" name="stipend_salary" placeholder="₹18,000 / mo" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 text-xs text-white focus:border-cyan-500 focus:outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-400 mb-1 font-semibold uppercase tracking-wider">Key Requirements / Description</label>
                        <textarea name="description" rows="3" placeholder="Brief technical requirements..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-xs text-white focus:border-cyan-500 focus:outline-none transition"></textarea>
                    </div>
                    <button type="submit" name="post_job" class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold py-3 rounded-xl text-xs transition duration-200 shadow-lg shadow-cyan-500/20">
                        Publish Listing
                    </button>
                </form>
            </div>

            <!-- Right: Candidate Applications -->
            <div class="lg:col-span-2 bg-slate-900/50 border border-slate-800 p-6 rounded-2xl space-y-4 shadow-xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-users text-cyan-400"></i> Received Candidate Profiles
                    </h2>
                    <span class="text-xs text-slate-400 font-medium">Review & Shortlist</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-slate-950 text-slate-400 uppercase font-semibold border-b border-slate-800">
                            <tr>
                                <th class="p-3.5">Candidate Details</th>
                                <th class="p-3.5">Applied For</th>
                                <th class="p-3.5">Verified Score</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            <?php if (!empty($applications)): ?>
                                <?php foreach ($applications as $app): ?>
                                    <tr class="hover:bg-slate-800/30 transition">
                                        <td class="p-3.5 font-semibold text-white">
                                            <div class="flex items-center gap-2">
                                                <span><?php echo htmlspecialchars($app['full_name']); ?></span>
                                                <button type="button" onclick='openCandidateModal(<?php echo json_encode($app, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)' class="text-cyan-400 hover:text-cyan-300 transition text-xs" title="View Full Profile">
                                                    <i class="fa-solid fa-circle-info"></i>
                                                </button>
                                            </div>
                                            <div class="text-[11px] text-slate-400 font-normal">
                                                <?php echo htmlspecialchars($app['college'] ?? 'College Not Set'); ?>
                                            </div>
                                        </td>
                                        <td class="p-3.5">
                                            <?php echo htmlspecialchars($app['job_title']); ?>
                                            <div class="text-[11px] text-cyan-400"><?php echo htmlspecialchars($app['company_name']); ?></div>
                                        </td>
                                        <td class="p-3.5">
                                            <span class="bg-slate-800 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded text-[11px] font-bold">
                                                <i class="fa-solid fa-award mr-1 text-[10px]"></i><?php echo round($app['candidate_score'], 1); ?>%
                                            </span>
                                        </td>
                                        <td class="p-3.5">
                                            <?php 
                                            $badge_color = match($app['status']) {
                                                'Shortlisted' => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                                                'Rejected'    => 'bg-red-500/15 text-red-400 border-red-500/30',
                                                default       => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30',
                                            };
                                            ?>
                                            <span class="<?php echo $badge_color; ?> border px-2.5 py-1 rounded-full text-[11px] font-bold inline-block">
                                                <?php echo htmlspecialchars($app['status']); ?>
                                            </span>
                                        </td>
                                        <td class="p-3.5 text-right space-x-1 whitespace-nowrap">
                                            <button type="button" onclick='openCandidateModal(<?php echo json_encode($app, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)' class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 px-2.5 py-1 rounded text-[11px] transition">
                                                View
                                            </button>
                                            <a href="dashboard.php?action=shortlist&app_id=<?php echo $app['id']; ?>" class="bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 px-2.5 py-1 rounded text-[11px] font-semibold transition">
                                                Shortlist
                                            </a>
                                            <a href="dashboard.php?action=reject&app_id=<?php echo $app['id']; ?>" class="bg-red-500/20 hover:bg-red-500/30 text-red-300 border border-red-500/30 px-2.5 py-1 rounded text-[11px] font-semibold transition">
                                                Reject
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 font-medium">No candidate applications received yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- Candidate Details Modal Pop-up -->
    <div id="candidateModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 space-y-5 shadow-2xl relative">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-id-badge text-cyan-400 text-lg"></i>
                    <h3 class="text-base font-bold text-white">Candidate Portfolio Summary</h3>
                </div>
                <button type="button" onclick="closeCandidateModal()" class="text-slate-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="space-y-3.5 text-xs text-slate-300">
                <div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800 flex justify-between items-center">
                    <div>
                        <p id="mName" class="text-sm font-bold text-white"></p>
                        <p id="mEmail" class="text-slate-400 text-[11px]"></p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] uppercase font-bold text-slate-500">Verified Score</span>
                        <div id="mScore" class="text-lg font-black text-emerald-400"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-950/70 p-3 rounded-xl border border-slate-800/80">
                        <span class="text-[10px] uppercase font-semibold text-slate-500 block">Institution</span>
                        <span id="mCollege" class="font-medium text-slate-200 mt-0.5 block"></span>
                    </div>
                    <div class="bg-slate-950/70 p-3 rounded-xl border border-slate-800/80">
                        <span class="text-[10px] uppercase font-semibold text-slate-500 block">Career Goal</span>
                        <span id="mGoal" class="font-medium text-cyan-400 mt-0.5 block"></span>
                    </div>
                </div>

                <div class="bg-slate-950/70 p-3 rounded-xl border border-slate-800/80">
                    <span class="text-[10px] uppercase font-semibold text-slate-500 block">Applied Designation</span>
                    <span id="mJob" class="font-semibold text-white mt-0.5 block"></span>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" onclick="closeCandidateModal()" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-4 py-2 rounded-xl text-xs transition">
                    Close Details
                </button>
            </div>
        </div>
    </div>

    <script>
        function openCandidateModal(candidate) {
            document.getElementById('mName').textContent = candidate.full_name;
            document.getElementById('mEmail').textContent = candidate.email;
            document.getElementById('mScore').textContent = Math.round(candidate.candidate_score) + '%';
            document.getElementById('mCollege').textContent = candidate.college || 'Not Specified';
            document.getElementById('mGoal').textContent = candidate.career_goal || 'Software Engineering';
            document.getElementById('mJob').textContent = candidate.job_title + ' (' + candidate.company_name + ')';
            document.getElementById('candidateModal').classList.remove('hidden');
        }

        function closeCandidateModal() {
            document.getElementById('candidateModal').classList.add('hidden');
        }
    </script>
</body>
</html>