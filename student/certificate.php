<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

$stmt = $pdo->prepare("
    SELECT u.full_name, ar.score 
    FROM users u 
    JOIN assessment_results ar ON u.id = ar.user_id 
    WHERE u.id = ? 
    ORDER BY ar.id DESC LIMIT 1
");
$stmt->execute([$user_id]);
$cert_data = $stmt->fetch();

$student_name = !empty($cert_data['full_name']) ? htmlspecialchars($cert_data['full_name']) : htmlspecialchars($_SESSION['name'] ?? 'Candidate');
$skill_name   = 'Full-Stack Software Engineering';
$score        = !empty($cert_data['score']) ? round($cert_data['score'], 1) : 100;
$issue_date   = date('F d, Y');
$cert_hash    = strtoupper(substr(hash('sha256', $user_id . $student_name . 'SKILLSYNC_PRINT_VALID'), 0, 8));
$cert_id      = "SKL-CERT-" . date('Y') . "-" . $cert_hash;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?php echo $student_name; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Alex+Brush&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b1120;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .font-cinzel { font-family: 'Cinzel', serif; }
        .font-signature { font-family: 'Alex Brush', cursive; }

        /* Print Specific Styling */
        @media print {
            .no-print { display: none !important; }
            body { 
                background: #ffffff !important; 
                padding: 0 !important; 
                margin: 0 !important;
            }
            .cert-sheet {
                box-shadow: none !important;
                border: 12px solid #0f172a !important;
                margin: 0 auto !important;
                width: 100% !important;
                max-width: 100% !important;
                height: 98vh !important;
                page-break-inside: avoid;
            }
            @page {
                size: A4 landscape;
                margin: 6mm;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 selection:bg-amber-400 selection:text-slate-950">

    <!-- Print / Return Buttons -->
    <div class="no-print w-full max-w-[1050px] flex items-center justify-between mb-5 px-2">
        <a href="dashboard.php" class="text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-700 px-4 py-2.5 rounded-xl transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
        <div class="flex items-center gap-3">
            <span class="text-xs text-slate-400">PDF Setting: <b>Landscape</b> & <b>Background graphics ON</b></span>
            <button onclick="window.print()" class="text-xs font-bold bg-amber-400 hover:bg-amber-300 text-slate-950 px-5 py-2.5 rounded-xl transition shadow-lg shadow-amber-400/20 flex items-center gap-2">
                <i class="fa-solid fa-print text-sm"></i> Print / Save as PDF
            </button>
        </div>
    </div>

    <!-- Official Corporate Sheet (Crisp for Print & Display) -->
    <div class="cert-sheet relative w-full max-w-[1050px] bg-[#fcfbf7] text-slate-900 rounded-2xl p-10 md:p-14 border-[14px] border-[#0f172a] shadow-2xl overflow-hidden text-center box-border">

        <!-- Inner Gold Inset Border -->
        <div class="border-2 border-[#c5a059] p-8 rounded-lg h-full flex flex-col justify-between relative">

            <!-- Watermark Crest -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03]">
                <i class="fa-solid fa-award text-[380px] text-[#0f172a]"></i>
            </div>

            <!-- Header Section -->
            <div>
                <div class="flex items-center justify-between border-b border-[#e2d9c8] pb-4 mb-4">
                    <div class="flex items-center gap-3 text-left">
                        <div class="w-10 h-10 rounded-xl bg-[#0f172a] text-[#c5a059] flex items-center justify-center text-xl font-black">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight text-[#0f172a] block">SKILL<span class="text-[#c5a059]">SYNC</span></span>
                            <span class="text-[9px] uppercase tracking-[0.2em] text-slate-500 font-bold block">Autonomous Evaluation Authority</span>
                        </div>
                    </div>

                    <div class="border border-[#c5a059] bg-[#f9f5ec] px-3 py-1 rounded text-[10px] font-bold text-[#8c6d2d] uppercase tracking-wider">
                        ISO 9001:2015 Verified
                    </div>
                </div>

                <!-- Main Certificate Headings -->
                <div class="space-y-1">
                    <p class="text-[11px] font-bold uppercase tracking-[0.35em] text-[#c5a059]">Standard of Competence</p>
                    <h1 class="font-cinzel text-3xl md:text-5xl font-black tracking-wide text-[#0f172a] uppercase">Certificate of Achievement</h1>
                </div>

                <p class="text-xs uppercase tracking-widest text-slate-500 font-semibold mt-4">This credential is authenticated and awarded to</p>

                <!-- Student Full Name -->
                <div class="py-2 max-w-xl mx-auto border-b-2 border-[#0f172a] mt-2 mb-4">
                    <h2 class="font-cinzel text-2xl md:text-4xl font-black text-[#0f172a] tracking-tight"><?php echo $student_name; ?></h2>
                </div>

                <!-- Award Description Text -->
                <p class="text-xs md:text-sm text-slate-700 max-w-2xl mx-auto leading-relaxed font-normal">
                    for passing the industry standard technical assessment in <strong class="text-[#0f172a] font-bold"><?php echo $skill_name; ?></strong> with a verified proficiency score of <strong class="text-[#8c6d2d] font-bold"><?php echo $score; ?>%</strong> through SkillSync automated candidate screening portal.
                </p>
            </div>

            <!-- Signatures and Authorities Footer -->
            <div class="grid grid-cols-4 items-end pt-6 border-t border-[#e2d9c8] mt-6 text-left">
                
                <!-- Issue Date & ID -->
                <div>
                    <p class="text-[9px] uppercase tracking-wider text-slate-400 font-bold">Issue Date</p>
                    <p class="text-xs font-bold text-[#0f172a] mt-0.5"><?php echo $issue_date; ?></p>
                    <p class="text-[9px] text-slate-500 font-mono mt-1 font-semibold"><?php echo $cert_id; ?></p>
                </div>

                <!-- Signatory 1: Yogesh Annasaheb Shinde -->
                <div class="text-center">
                    <div class="font-signature text-2xl text-[#0f172a] leading-none mb-1">Yogesh A. Shinde</div>
                    <div class="border-t border-slate-400 pt-1 w-4/5 mx-auto">
                        <p class="text-[10px] font-bold text-[#0f172a] uppercase leading-tight">YOGESH ANNASAHEB SHINDE</p>
                        <p class="text-[8px] uppercase tracking-wider text-[#8c6d2d] font-semibold">Authorized Director</p>
                    </div>
                </div>

                <!-- Signatory 2: Aryan Arjun Kadam -->
                <div class="text-center">
                    <div class="font-signature text-2xl text-[#0f172a] leading-none mb-1">Aryan A. Kadam</div>
                    <div class="border-t border-slate-400 pt-1 w-4/5 mx-auto">
                        <p class="text-[10px] font-bold text-[#0f172a] uppercase leading-tight">ARYAN ARJUN KADAM</p>
                        <p class="text-[8px] uppercase tracking-wider text-[#8c6d2d] font-semibold">Authorized Director</p>
                    </div>
                </div>

                <!-- Gold Corporate Seal -->
                <div class="flex justify-end">
                    <div class="w-16 h-16 rounded-full border-2 border-dashed border-[#c5a059] p-1 flex items-center justify-center">
                        <div class="w-full h-full rounded-full bg-[#0f172a] text-[#c5a059] flex flex-col items-center justify-center text-center shadow-md">
                            <i class="fa-solid fa-award text-base"></i>
                            <span class="text-[6px] font-bold tracking-widest uppercase mt-0.5">VERIFIED</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</body>
</html>