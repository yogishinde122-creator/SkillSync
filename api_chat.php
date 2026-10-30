<?php
header('Content-Type: application/json');

$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);
$userMessage = trim($data['message'] ?? '');

if (empty($userMessage)) {
    echo json_encode(['reply' => 'Please type your question or query to proceed.']);
    exit;
}

$lower = strtolower($userMessage);

// 1. GREETINGS
if (preg_match('/\b(hey|hi|hello|greetings|morning|afternoon|evening|sup|yo)\b/', $lower)) {
    echo json_encode([
        'reply' => "Hello there! Welcome to <strong>SkillSync AI Assistant</strong>.<br><br>I am your dedicated career and technical advisor. How can I help you today? You can ask about:<br>• Candidate Skill Assessment Tracks<br>• Relational Database (SQL) & Full-Stack (PHP) concepts<br>• Corporate Placement & Internship Guidelines<br>• Platform Founders & Technical Architecture"
    ]);
    exit;
}

// 2. FOUNDERS & DIRECTORS
if (preg_match('/\b(founder|founders|creator|creators|architect|lead|team|who made|who developed|develop)\b/', $lower)) {
    echo json_encode([
        'reply' => "The SkillSync platform was designed and engineered by:<br><br>• <strong>Yogesh Annasaheb Shinde</strong> — Co-Founder & Chief Systems Architect (Lead Architect of Core Framework, Security RBAC, Database Schema, and PDO Execution Pipelines).<br>• <strong>Aryan Arjun Kadam</strong> — Co-Founder & Platform Director (Lead Director for Corporate Benchmarks, Student Evaluation Tracks, and Recruiter Engagement).<br><br>Developed under the Community Engagement Program (CEP) for TYBBA(CA) 2026."
    ]);
    exit;
}

// 3. ABOUT PORTAL / SKILLSYNC CEP
if (preg_match('/\b(portal|skillsync|about|platform|project|initiative|system|cep)\b/', $lower)) {
    echo json_encode([
        'reply' => "<strong>SkillSync</strong> is an Autonomous Career Enhancement Platform (CEP) engineered to eliminate recruitment friction.<br><br><strong>Key Capabilities:</strong><br>1. <strong>Automated Skill Diagnostics:</strong> Evaluates students on real-world coding benchmarks instead of pure theory.<br>2. <strong>Cryptographic Credentials:</strong> Issues tamper-proof digital certificates authorized by platform signatories.<br>3. <strong>Corporate Matching:</strong> Direct hiring channel connecting verified candidates to tier-1 companies without middlemen."
    ]);
    exit;
}

// 4. INTERNSHIPS, JOBS & PLACEMENT
if (preg_match('/\b(internship|internships|job|jobs|apply|placement|hire|shortlist|recruitment)\b/', $lower)) {
    echo json_encode([
        'reply' => "<strong>Corporate Internship Application Workflow:</strong><br><br>1. <strong>Sign Up / Login:</strong> Access your student dashboard via 'Get Started'.<br>2. <strong>Take Technical Diagnostic:</strong> Score above benchmark thresholds in backend, database, and interface logic.<br>3. <strong>Direct Submission:</strong> Browse the 'Available Internships' tab and click 'Apply Now'. Verified corporate recruiters inspect your scorecards and shortlist you directly."
    ]);
    exit;
}

// 5. CODING & TECHNICAL QUERIES (PHP, SQL, FULL-STACK)
if (preg_match('/\b(php|pdo|sql|mysql|database|schema|normalization|join|backend)\b/', $lower)) {
    echo json_encode([
        'reply' => "<strong>Technical System Architecture Analysis:</strong><br><br>• <strong>PHP PDO Layer:</strong> SkillSync utilizes PHP Data Objects (PDO) with parameterized prepared statements to eliminate SQL Injection attacks.<br>• <strong>Database Optimization:</strong> MySQL schema is structured in 3rd Normal Form (3NF) with foreign key constraints (ON DELETE CASCADE) to ensure referential integrity.<br>• <strong>Session Integrity:</strong> Hardened cookie sessions isolate Candidate, Recruiter, and Admin access tiers."
    ]);
    exit;
}

if (preg_match('/\b(ui|ux|tailwind|css|design|three|3d|frontend)\b/', $lower)) {
    echo json_encode([
        'reply' => "<strong>Frontend & 3D Ergonomics Overview:</strong><br><br>• <strong>Tailwind CSS Framework:</strong> Provides responsive layout viewports with corporate contrast standards.<br>• <strong>Three.js Mesh:</strong> Employs a WebGL buffer particle pipeline in `index.php` that calculates mouse vector coordinates for real-time interactive motion."
    ]);
    exit;
}

// 6. INTERVIEW & CAREER ADVICE
if (preg_match('/\b(tip|tips|interview|advice|prepare|guidance|roadmap)\b/', $lower)) {
    echo json_encode([
        'reply' => "<strong>Career Preparation Directives:</strong><br><br>• <strong>Master Fundamentals:</strong> Focus on writing secure backend APIs and understanding ACID properties in relational databases.<br>• <strong>Build Practical Repositories:</strong> Maintain a verifiable GitHub portfolio demonstrating end-to-end CRUD operations.<br>• <strong>Earn Verified Badges:</strong> Clear the SkillSync 3D Diagnostic Test to prove your competency to corporate recruiters."
    ]);
    exit;
}

// 7. CONTACT & HELP DESK
if (preg_match('/\b(contact|support|phone|mobile|number|email|call|help)\b/', $lower)) {
    echo json_encode([
        'reply' => "<strong>SkillSync Direct Help Desk:</strong><br><br>• <strong>Phone / WhatsApp:</strong> +91 7387147337 / +91 9552360126<br>• <strong>Official Email:</strong> support@skillsync.edu.in<br>• <strong>Campus Authority:</strong> Pune, Maharashtra, India."
    ]);
    exit;
}

// 8. GENERAL KNOWLEDGE / OPEN QUERY INTELLIGENT HANDLER
echo json_encode([
    'reply' => "Regarding \"<strong>" . htmlspecialchars($userMessage) . "</strong>\":<br><br>SkillSync is an autonomous career accelerator designed to assess and prepare next-generation software professionals.<br><br>• <strong>Need technical testing?</strong> Run the 3D Diagnostic Simulator in the Diagnostics section.<br>• <strong>Looking for internships?</strong> Register as a candidate to access verified corporate roles.<br>• <strong>Need assistance?</strong> Feel free to ask about our founders, system architecture, or career roadmaps!"
]);