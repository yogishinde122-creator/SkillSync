<?php
// Initialize database connection
if (file_exists('../config/db.php')) {
    require_once '../config/db.php';
} elseif (file_exists('../Config/db.php')) {
    require_once '../Config/db.php';
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $mobile    = trim($_POST['mobile']);
    $password  = $_POST['password'];
    $role      = $_POST['role'];
    $college   = trim($_POST['college'] ?? '');

    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All required fields must be filled.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "This email is already registered.";
        } else {
            $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
            $insert = $pdo->prepare("INSERT INTO users (full_name, email, mobile, password, role) VALUES (?, ?, ?, ?, ?)");
            if ($insert->execute([$full_name, $email, $mobile, $hashed_pass, $role])) {
                $user_id = $pdo->lastInsertId();
                if ($role === 'student') {
                    $prof = $pdo->prepare("INSERT INTO student_profiles (user_id, college) VALUES (?, ?)");
                    $prof->execute([$user_id, $college]);
                }
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SkillSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-slate-200 min-h-screen flex items-center justify-center p-4 selection:bg-cyan-500 selection:text-slate-950">

    <div data-aos="zoom-in" class="max-w-md w-full bg-slate-900/90 border border-slate-800 rounded-2xl p-8 shadow-2xl backdrop-blur-xl">
        <div class="text-center mb-6">
            <a href="../index.php" class="inline-flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-cyan-500 flex items-center justify-center text-slate-950 font-black text-base shadow-md">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="text-xl font-bold tracking-wider text-white">Skill<span class="text-cyan-400">Sync</span></span>
            </a>
            <h2 class="text-2xl font-bold text-white">Create Account</h2>
            <p class="text-xs text-slate-400 mt-1">Join the autonomous career ecosystem</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-300 text-xs p-3 rounded-lg mb-4 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">Full Name</label>
                <input type="text" name="full_name" required placeholder="Rahul More" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:border-cyan-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">Email</label>
                <input type="email" name="email" required placeholder="rahul@example.com" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:border-cyan-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">Mobile</label>
                <input type="text" name="mobile" required placeholder="9876543210" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:border-cyan-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">Role</label>
                <select name="role" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm focus:border-cyan-500 focus:outline-none text-slate-300">
                    <option value="student">Student / Learner</option>
                    <option value="mentor">Community Mentor</option>
                    <option value="company">Corporate / Recruiter</option>
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">College / Organization</label>
                <input type="text" name="college" placeholder="College or University Name" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:border-cyan-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs uppercase font-semibold text-slate-400 mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sm text-white focus:border-cyan-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold py-3 rounded-lg text-sm mt-4 transition shadow-lg shadow-cyan-500/25">
                Register
            </button>
        </form>
        <p class="text-xs text-center text-slate-400 mt-6">
            Already have an account? <a href="login.php" class="text-cyan-400 hover:underline">Log in</a>
        </p>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>