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
$job_id  = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

if ($job_id > 0) {
    // Check existing application using student_id column
    $check_stmt = $pdo->prepare("SELECT id FROM job_applications WHERE student_id = ? AND job_id = ?");
    $check_stmt->execute([$user_id, $job_id]);

    if ($check_stmt->rowCount() == 0) {
        // Insert application record using student_id
        $stmt = $pdo->prepare("INSERT INTO job_applications (student_id, job_id, status) VALUES (?, ?, 'Applied')");
        $stmt->execute([$user_id, $job_id]);
    }
}

// Redirect back to dashboard
header("Location: dashboard.php?applied=1");
exit;
?>