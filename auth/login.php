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

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(email) = LOWER(?)");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Check hashed password or plain text
            $is_valid = password_verify($password, $user['password']) || ($password === $user['password']);

            if ($is_valid) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = strtolower(trim($user['role']));
                $_SESSION['name']    = $user['full_name'];

                // Route by role
                if ($_SESSION['role'] === 'company' || $_SESSION['role'] === 'recruiter' || $_SESSION['role'] === 'mentor') {
                    header("Location: ../company/dashboard.php");
                    exit;
                } else {
                    header("Location: ../student/dashboard.php");
                    exit;
                }
            } else {
                $error = "Incorrect password. Please try again.";
            }
        } else {
            $error = "Account not found with this email.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SkillSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-slate-200 min-h-screen flex items-center justify-center p-4 selection:bg-cyan-500 selection:text-slate-950">

    <div class="w-full max-w-md bg-slate-900/80 border border-slate-800 rounded-2xl p-8 space-y-6 shadow-2xl backdrop-blur-md">
        <div class="text-center space-y-2">
            <div class="inline-flex w-12 h-12 rounded-xl bg-cyan-500 items-center justify-center text-slate-950 font-black text-2xl mb-1 shadow-lg shadow-cyan-500/20">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Welcome Back</h1>
            <p class="text-xs text-slate-400">Enter your credentials to access SkillSync</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-500/15 border border-red-500/40 text-red-300 text-xs p-3.5 rounded-xl text-center font-medium flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-sm text-red-400"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off" class="space-y-4">
            <div>
                <label class="block text-xs uppercase font-semibold text-slate-400 mb-1.5 tracking-wider">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-xs pointer-events-none">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="email" name="email" required autocomplete="off" placeholder="name@example.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white placeholder-slate-600 focus:border-cyan-500 focus:outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase font-semibold text-slate-400 mb-1.5 tracking-wider">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 text-xs pointer-events-none">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white placeholder-slate-600 focus:border-cyan-500 focus:outline-none transition">
                </div>
            </div>

            <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold py-3 rounded-xl text-xs transition duration-200 shadow-lg shadow-cyan-500/25 flex items-center justify-center gap-2">
                <span>Sign In to Dashboard</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <p class="text-center text-xs text-slate-500 pt-2 border-t border-slate-800/80">
            Don't have an account? <a href="register.php" class="text-cyan-400 hover:underline font-semibold ml-1">Create one here</a>
        </p>
    </div>

</body>
</html>