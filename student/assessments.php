<?php
// Initialize database connection
if (file_exists('../config/db.php')) {
    require_once '../config/db.php';
} elseif (file_exists('../Config/db.php')) {
    require_once '../Config/db.php';
}

// Ensure user is authenticated as student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$calculated_score = null;

// Handle assessment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assessment_id = (int)$_POST['assessment_id'];
    $answers = $_POST['answers'] ?? [];

    // Fetch correct options from database
    $stmt = $pdo->prepare("SELECT id, correct_option FROM questions WHERE assessment_id = ?");
    $stmt->execute([$assessment_id]);
    $questions = $stmt->fetchAll();

    $total_questions = count($questions);
    $correct_count = 0;

    foreach ($questions as $q) {
        $qid = $q['id'];
        if (isset($answers[$qid]) && strtoupper($answers[$qid]) === strtoupper($q['correct_option'])) {
            $correct_count++;
        }
    }

    $calculated_score = ($total_questions > 0) ? round(($correct_count / $total_questions) * 100) : 0;

    // Save performance result to database
    $insert = $pdo->prepare("INSERT INTO assessment_results (user_id, assessment_id, score, status) VALUES (?, ?, ?, 'Completed')");
    $insert->execute([$user_id, $assessment_id, $calculated_score]);

    $success_message = "Assessment submitted successfully! Your score has been recorded.";
}

// Fetch active assessment details
$assessment_stmt = $pdo->query("SELECT * FROM assessments LIMIT 1");
$current_assessment = $assessment_stmt->fetch();

$questions_list = [];
if ($current_assessment) {
    $q_stmt = $pdo->prepare("SELECT * FROM questions WHERE assessment_id = ?");
    $q_stmt->execute([$current_assessment['id']]);
    $questions_list = $q_stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Assessment | SkillSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { background-color: #030712; }</style>
</head>
<body class="text-slate-200 min-h-screen flex flex-col selection:bg-cyan-500 selection:text-slate-950">

    <!-- Header Navigation -->
    <header class="border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="dashboard.php" class="flex items-center gap-2 text-slate-300 hover:text-white transition text-xs font-semibold">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-cyan-500 flex items-center justify-center text-slate-950 font-black text-xs">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="text-sm font-bold text-white tracking-wide">Skill<span class="text-cyan-400">Sync</span></span>
            </div>
        </div>
    </header>

    <!-- Assessment Container -->
    <main class="max-w-3xl mx-auto px-6 py-10 flex-1 w-full space-y-6">
        
        <?php if ($success_message): ?>
            <div class="bg-emerald-500/10 border border-emerald-500/40 p-6 rounded-2xl text-center space-y-3">
                <i class="fa-solid fa-circle-check text-3xl text-emerald-400"></i>
                <h2 class="text-xl font-bold text-white">Assessment Completed</h2>
                <p class="text-xs text-slate-300"><?php echo $success_message; ?></p>
                <div class="text-4xl font-black text-cyan-400 mt-2"><?php echo $calculated_score; ?>%</div>
                <div class="pt-4">
                    <a href="dashboard.php" class="inline-block bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-6 py-2.5 rounded-xl text-xs transition shadow-lg shadow-cyan-500/20">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        <?php else: ?>

            <div class="bg-slate-900/70 border border-slate-800 rounded-2xl p-6">
                <span class="text-xs font-semibold bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 px-3 py-1 rounded-full uppercase tracking-wider">
                    <?php echo htmlspecialchars($current_assessment['skill_name'] ?? 'Skill Test'); ?>
                </span>
                <h1 class="text-2xl font-bold text-white mt-3"><?php echo htmlspecialchars($current_assessment['title'] ?? 'Skill Assessment'); ?></h1>
                <p class="text-xs text-slate-400 mt-1">Answer the following questions to verify your competency score.</p>
            </div>

            <form method="POST" class="space-y-6">
                <input type="hidden" name="assessment_id" value="<?php echo htmlspecialchars($current_assessment['id'] ?? 1); ?>">

                <?php foreach ($questions_list as $index => $q): ?>
                    <div class="bg-slate-900/40 border border-slate-800 rounded-xl p-6 space-y-4">
                        <p class="text-sm font-semibold text-white">
                            <span class="text-cyan-400 mr-2">Q<?php echo $index + 1; ?>.</span>
                            <?php echo htmlspecialchars($q['question_text']); ?>
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-2">
                            <?php 
                            $options = [
                                'A' => $q['option_a'],
                                'B' => $q['option_b'],
                                'C' => $q['option_c'],
                                'D' => $q['option_d']
                            ];
                            foreach ($options as $opt_key => $opt_val): 
                            ?>
                                <label class="flex items-center gap-3 bg-slate-950 border border-slate-800 p-3 rounded-lg hover:border-cyan-500/50 cursor-pointer transition">
                                    <input type="radio" name="answers[<?php echo $q['id']; ?>]" value="<?php echo $opt_key; ?>" required class="text-cyan-500 focus:ring-cyan-500">
                                    <span class="text-xs text-slate-300">
                                        <strong class="text-slate-400 mr-1"><?php echo $opt_key; ?>.</strong> <?php echo htmlspecialchars($opt_val); ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold py-3.5 rounded-xl text-sm transition shadow-lg shadow-cyan-500/25">
                    Submit Assessment
                </button>
            </form>

        <?php endif; ?>

    </main>
</body>
</html>