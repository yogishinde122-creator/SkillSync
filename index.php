<?php
if (file_exists('config/db.php')) {
    require_once 'config/db.php';
} elseif (file_exists('Config/db.php')) {
    require_once 'Config/db.php';
} else {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSync | 3D Autonomous Career Acceleration</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,700;1,400;1,600&display=swap" rel="stylesheet">

    <style>
        body { 
            background-color: #030712; 
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        .font-serif-luxury {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .glass-panel {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(56, 189, 248, 0.2);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            transition: all 0.3s ease;
        }
        .glass-panel:hover {
            border-color: rgba(56, 189, 248, 0.5);
            transform: translateY(-3px);
        }
        .glow-text {
            background: linear-gradient(135deg, #ffffff 0%, #38bdf8 50%, #818cf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        #hero-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        /* Pure CSS Hero Entry Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-badge {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-fade-title {
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards;
            opacity: 0;
        }
        .animate-fade-desc {
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards;
            opacity: 0;
        }
        .animate-fade-cta {
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.45s forwards;
            opacity: 0;
        }
    </style>
</head>
<body class="text-slate-100 selection:bg-cyan-500 selection:text-slate-950 min-h-screen relative flex flex-col">

    <!-- Cinematic Background Video Layer -->
    <div class="fixed inset-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <video class="w-full h-full object-cover opacity-25" autoplay muted loop playsinline>
            <source src="https://pub-1e5b4001b36b47e28e6a2fb775966a79.r2.dev/templates/orchid/hero.mp4" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-t from-[#030712] via-[#030712]/80 to-[#030712]/40"></div>
    </div>

    <!-- 3D Mesh Particle Canvas -->
    <canvas id="hero-canvas"></canvas>

    <!-- Top Sticky Header -->
    <nav class="sticky top-0 z-50 px-6 py-4 border-b border-white/10 glass-panel !rounded-none !border-x-0 !border-t-0">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-500 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-cyan-500/30">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <span class="text-2xl font-black tracking-wider text-white">Skill<span class="text-cyan-400">Sync</span></span>
                    <span class="block text-[8px] uppercase tracking-widest text-slate-400 font-mono">CEP Autonomous Engine</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-7 text-xs font-semibold uppercase tracking-widest text-slate-300">
                <a href="#about" class="hover:text-cyan-400 transition">About CEP</a>
                <a href="#features" class="hover:text-cyan-400 transition">3D Diagnostics</a>
                <a href="#stats" class="hover:text-cyan-400 transition">Impact Stats</a>
                <a href="#leadership" class="hover:text-cyan-400 transition">Founders</a>
                <a href="#share" class="hover:text-cyan-400 transition text-emerald-400 font-bold">Share</a>
                <a href="#faq" class="hover:text-cyan-400 transition">Help FAQ</a>
                <a href="#contact" class="hover:text-cyan-400 transition text-cyan-300 font-bold">Contact Us</a>
            </div>

            <div class="flex items-center gap-4">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo htmlspecialchars($_SESSION['role'] ?? 'student'); ?>/dashboard.php" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-cyan-500/25">
                        <i class="fa-solid fa-gauge mr-1"></i> Dashboard
                    </a>
                <?php else: ?>
                    <a href="auth/login.php" class="text-slate-300 hover:text-white text-xs uppercase font-semibold tracking-wider">Log In</a>
                    <a href="auth/register.php" class="bg-gradient-to-r from-cyan-500 to-indigo-600 hover:opacity-90 text-white font-bold px-5 py-2.5 rounded-xl text-xs uppercase tracking-wider transition shadow-lg shadow-cyan-500/25">
                        Get Started
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Main Container with Auto-Fade Motion Entry -->
    <main class="relative z-10 flex-grow">
        <section class="max-w-7xl mx-auto px-6 pt-16 pb-20 text-center">
            
            <!-- Animated Badge -->
            <div class="animate-fade-badge inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-cyan-500/30 bg-cyan-950/40 text-cyan-300 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span> Community Engagement Program (CEP) • TYBBA(CA) 2026
            </div>

            <!-- Animated Headline (Fade In + Slide Up) -->
            <h1 class="animate-fade-title text-4xl sm:text-6xl lg:text-7xl font-bold font-serif-luxury text-white tracking-tight leading-[1.1] max-w-5xl mx-auto">
                Accelerate <em class="italic text-cyan-400 font-normal">Your Skills.</em> <br>
                <span class="glow-text">Bridge Underserved Industry Gaps.</span>
            </h1>

            <!-- Animated Description -->
            <p class="animate-fade-desc mt-6 text-sm sm:text-base text-slate-400 max-w-2xl mx-auto leading-relaxed">
                An intelligent career accelerator combining verified skill evaluations, algorithmic roadmap diagnostics, and direct industry placement tracks.
            </p>

            <!-- Animated Buttons -->
            <div class="animate-fade-cta mt-8 flex flex-wrap justify-center gap-4">
                <a href="auth/register.php" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-7 py-3 rounded-xl transition shadow-xl shadow-cyan-500/30 flex items-center gap-2 text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-play"></i> Start Career Track
                </a>
                <a href="auth/login.php" class="glass-panel text-white font-semibold px-7 py-3 rounded-xl transition flex items-center gap-2 text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-building-columns text-cyan-400"></i> Corporate Access
                </a>
                <a href="#share" class="glass-panel text-emerald-300 border-emerald-500/40 hover:bg-emerald-500/10 font-semibold px-6 py-3 rounded-xl transition flex items-center gap-2 text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-share-nodes"></i> Share
                </a>
                <a href="#contact" class="glass-panel text-cyan-300 border-cyan-500/40 hover:bg-cyan-500/10 font-semibold px-6 py-3 rounded-xl transition flex items-center gap-2 text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-envelope"></i> Contact Desk
                </a>
            </div>

            <!-- About CEP Section -->
            <div class="mt-20 max-w-5xl mx-auto text-left glass-panel p-8 rounded-3xl" id="about">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-circle-info"></i>
                    </span>
                    <h2 class="text-xl font-bold text-white font-serif-luxury">About The CEP Initiative</h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-light">
                    The Community Engagement Program (CEP) bridge connects undergraduate engineering talent directly with corporate opportunities. We eliminate recruitment ambiguity through standardized skill assessments, verified evaluation scorecards, and verifiable digital achievement credentials.
                </p>
            </div>

            <!-- 3D Diagnostics Features -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-12 max-w-6xl mx-auto text-left" id="features">
                <div class="glass-panel p-6 rounded-2xl">
                    <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                    <span class="text-xs font-mono text-cyan-400 font-bold">01 / DIAGNOSIS</span>
                    <h3 class="text-base font-bold text-white mt-1">Skill Gap Engine</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Algorithmic tests instantly isolate concept gaps and construct tailored roadmaps.</p>
                </div>

                <div class="glass-panel p-6 rounded-2xl">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-vial-circle-check"></i>
                    </div>
                    <span class="text-xs font-mono text-indigo-400 font-bold">02 / BENCHMARK</span>
                    <h3 class="text-base font-bold text-white mt-1">Live Evaluations</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Dynamic SQL, architecture, and logic evaluations with instant grade certificates.</p>
                </div>

                <div class="glass-panel p-6 rounded-2xl">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-handshake-angle"></i>
                    </div>
                    <span class="text-xs font-mono text-emerald-400 font-bold">03 / MENTORSHIP</span>
                    <h3 class="text-base font-bold text-white mt-1">Community Mentors</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Peer-to-peer volunteers and industry seniors guide tier-2/3 college students.</p>
                </div>

                <div class="glass-panel p-6 rounded-2xl">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                    <span class="text-xs font-mono text-amber-400 font-bold">04 / PLACEMENT</span>
                    <h3 class="text-base font-bold text-white mt-1">Grassroot Internships</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Validated candidates connect directly with civic initiatives and partner agencies.</p>
                </div>
            </div>

            <!-- Stats Metrics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12 max-w-5xl mx-auto" id="stats">
                <div class="glass-panel p-5 rounded-xl text-center">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">5,000+</h3>
                    <p class="text-[11px] text-slate-400 uppercase font-mono tracking-wider mt-1">Learners Empowered</p>
                </div>
                <div class="glass-panel p-5 rounded-xl text-center">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-cyan-400">100%</h3>
                    <p class="text-[11px] text-slate-400 uppercase font-mono tracking-wider mt-1">Free Access Model</p>
                </div>
                <div class="glass-panel p-5 rounded-xl text-center">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-indigo-400">94%</h3>
                    <p class="text-[11px] text-slate-400 uppercase font-mono tracking-wider mt-1">Placement Readiness</p>
                </div>
                <div class="glass-panel p-5 rounded-xl text-center">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">50+</h3>
                    <p class="text-[11px] text-slate-400 uppercase font-mono tracking-wider mt-1">Civic Partners</p>
                </div>
            </div>

            <!-- Founders Section -->
            <div class="mt-20 pt-12 border-t border-slate-800/80 max-w-4xl mx-auto" id="leadership">
                <div class="text-center mb-8">
                    <span class="text-xs font-mono uppercase tracking-widest text-cyan-400">Leadership & Architecture</span>
                    <h2 class="text-2xl font-bold text-white mt-1 font-serif-luxury">The Minds Behind SkillSync</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="glass-panel p-6 rounded-2xl flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-cyan-500/20 border border-cyan-500/40 flex items-center justify-center text-cyan-400 text-xl font-bold">YS</div>
                        <div class="text-left">
                            <h3 class="text-base font-bold text-white">Yogesh Annasaheb Shinde</h3>
                            <p class="text-xs text-cyan-400 font-semibold uppercase tracking-wider">Founder & Chief Architect</p>
                            <p class="text-xs text-slate-400 mt-1 font-light">Core Architecture & Systems Design</p>
                        </div>
                    </div>

                    <div class="glass-panel p-6 rounded-2xl flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-indigo-500/20 border border-indigo-500/40 flex items-center justify-center text-indigo-400 text-xl font-bold">AK</div>
                        <div class="text-left">
                            <h3 class="text-base font-bold text-white">Aryan Arjun Kadam</h3>
                            <p class="text-xs text-indigo-400 font-semibold uppercase tracking-wider">Co-Founder & Executive Director</p>
                            <p class="text-xs text-slate-400 mt-1 font-light">Community Outreach & Operations</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Share Platform -->
            <div class="mt-20 pt-12 border-t border-slate-800/80 max-w-4xl mx-auto text-center" id="share">
                <span class="text-xs font-mono uppercase tracking-widest text-emerald-400">Platform Reach</span>
                <h2 class="text-2xl font-bold text-white mt-1 font-serif-luxury">Share SkillSync Platform</h2>
                <p class="text-xs text-slate-400 mt-2 max-w-lg mx-auto">Invite students, fellow developers, and recruiter networks to experience verified skill evaluations:</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                    <a href="https://api.whatsapp.com/send?text=Check%20out%20SkillSync%20-%20Autonomous%20Career%20Acceleration%20Platform!%20http://localhost/SKILLSYNC" target="_blank" class="glass-panel p-4 rounded-xl flex items-center justify-center gap-3 text-emerald-400 text-xs font-bold hover:bg-emerald-500/10 transition">
                        <i class="fa-brands fa-whatsapp text-2xl"></i> WhatsApp
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=http://localhost/SKILLSYNC" target="_blank" class="glass-panel p-4 rounded-xl flex items-center justify-center gap-3 text-cyan-400 text-xs font-bold hover:bg-cyan-500/10 transition">
                        <i class="fa-brands fa-linkedin text-2xl"></i> LinkedIn
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Check%20out%20SkillSync%20Platform&url=http://localhost/SKILLSYNC" target="_blank" class="glass-panel p-4 rounded-xl flex items-center justify-center gap-3 text-sky-400 text-xs font-bold hover:bg-sky-500/10 transition">
                        <i class="fa-brands fa-x-twitter text-2xl"></i> Twitter / X
                    </a>
                </div>

                <div class="mt-6 max-w-md mx-auto flex gap-2">
                    <input id="portalShareLink" type="text" readonly value="http://localhost/SKILLSYNC" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-4 py-2 text-xs text-slate-300 font-mono focus:outline-none">
                    <button onclick="copyShareUrl()" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-5 py-2 rounded-xl text-xs uppercase tracking-wider transition whitespace-nowrap">
                        Copy Link
                    </button>
                </div>
                <div id="copySuccessMsg" class="hidden text-xs text-emerald-400 mt-2 font-semibold">
                    <i class="fa-solid fa-check mr-1"></i> Link copied to clipboard successfully!
                </div>
            </div>

            <!-- Help & FAQ Section -->
            <div class="mt-20 pt-12 border-t border-slate-800/80 max-w-4xl mx-auto text-left" id="faq">
                <div class="text-center mb-10">
                    <span class="text-xs font-mono uppercase tracking-widest text-indigo-400">Support & Guidance</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-white mt-1 font-serif-luxury">Frequently Asked Questions</h2>
                    <p class="text-xs text-slate-400 mt-2">Comprehensive answers on technical evaluation, digital credentials, and hiring workflows.</p>
                </div>

                <div class="space-y-3.5">
                    <!-- FAQ 1 -->
                    <details class="group glass-panel p-5 rounded-2xl cursor-pointer">
                        <summary class="flex justify-between items-center text-sm font-semibold text-white">
                            <span>1. How does the automated skill diagnostic assessment work?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-xs text-slate-400 mt-3 leading-relaxed font-light">
                            Students undergo diagnostic evaluations covering Core PHP logic, Relational MySQL schema design, and responsive design systems. Test results are processed algorithmically and immediately stored in the database to generate verified scores.
                        </p>
                    </details>

                    <!-- FAQ 2 -->
                    <details class="group glass-panel p-5 rounded-2xl cursor-pointer">
                        <summary class="flex justify-between items-center text-sm font-semibold text-white">
                            <span>2. How is the SkillSync certificate authenticated and verified?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-xs text-slate-400 mt-3 leading-relaxed font-light">
                            Each generated certificate carries a cryptographic verification key (e.g., SS-DIR-YS01 / SS-DIR-AK02) and a unique system-generated hash. Employers can verify credential authenticity directly without third-party validation agencies.
                        </p>
                    </details>

                    <!-- FAQ 3 -->
                    <details class="group glass-panel p-5 rounded-2xl cursor-pointer">
                        <summary class="flex justify-between items-center text-sm font-semibold text-white">
                            <span>3. How do corporate recruiters review and shortlist applicants?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-xs text-slate-400 mt-3 leading-relaxed font-light">
                            Recruiters access a dedicated corporate suite where submitted applications appear in real time. They can inspect candidate profiles, review evaluated skill scores, and update statuses to "Shortlisted" or "Rejected" with a single click.
                        </p>
                    </details>

                    <!-- FAQ 4 -->
                    <details class="group glass-panel p-5 rounded-2xl cursor-pointer">
                        <summary class="flex justify-between items-center text-sm font-semibold text-white">
                            <span>4. Is the platform free for undergraduate college candidates?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-xs text-slate-400 mt-3 leading-relaxed font-light">
                            Yes. Under the Community Engagement Program (CEP) initiative, account registration, skill diagnostics, roadmap generation, and internship applications are 100% free for students from tier-2 and tier-3 colleges.
                        </p>
                    </details>

                    <!-- FAQ 5 -->
                    <details class="group glass-panel p-5 rounded-2xl cursor-pointer">
                        <summary class="flex justify-between items-center text-sm font-semibold text-white">
                            <span>5. Can students re-attempt tests to improve their score?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-xs text-slate-400 mt-3 leading-relaxed font-light">
                            Yes, learners can retake modular assessments after completing recommended roadmap practice tasks. Updated test scores sync automatically across current job applications and candidate profile views.
                        </p>
                    </details>

                    <!-- FAQ 6 -->
                    <details class="group glass-panel p-5 rounded-2xl cursor-pointer">
                        <summary class="flex justify-between items-center text-sm font-semibold text-white">
                            <span>6. How do I contact the technical desk for onboarding issues?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-xs text-slate-400 mt-3 leading-relaxed font-light">
                            You can dispatch an inquiry using the contact form below or reach our founders' helpline directly via WhatsApp at +91 7387147337 / +91 9552360126. Support queries are resolved within standard working hours.
                        </p>
                    </details>
                </div>
            </div>
            <!-- Contact & Enquiries Section -->
            <div class="mt-20 pt-12 border-t border-slate-800/80 max-w-5xl mx-auto text-left" id="contact">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div class="space-y-4">
                        <span class="text-xs font-mono uppercase tracking-widest text-cyan-400">Direct Enquiries</span>
                        <h2 class="text-2xl font-bold text-white font-serif-luxury">Contact Support Desk</h2>
                        <p class="text-xs text-slate-400 leading-relaxed font-light">
                            Have questions regarding candidate evaluations, institutional tie-ups, or recruiter onboarding? Reach out directly to the founders' desk.
                        </p>
                        
                        <div class="space-y-3 pt-3 text-xs text-slate-300">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-location-dot text-cyan-400 text-base"></i>
                                <span>SkillSync Authority, Pune, Maharashtra, India</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-envelope text-cyan-400 text-base"></i>
                                <a href="mailto:support@skillsync.edu.in" class="hover:underline">support@skillsync.edu.in</a>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-phone text-cyan-400 text-base"></i>
                                <span>+91 7387147337 / +91 9552360126</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fa-brands fa-whatsapp text-emerald-400 text-base"></i>
                                <a href="https://api.whatsapp.com/send?phone=917387147337" target="_blank" class="hover:underline text-emerald-400 font-semibold">Chat on WhatsApp (+91 7387147337)</a>
                            </div>
                        </div>
                    </div>

                    <!-- Interactive Enquiry Form -->
                    <div class="glass-panel p-6 rounded-2xl space-y-3.5">
                        <div id="contactSentFeedback" class="hidden bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs p-3 rounded-xl">
                            <i class="fa-solid fa-circle-check mr-1.5 text-emerald-400"></i> Enquiry dispatched! The founders will respond shortly.
                        </div>

                        <form onsubmit="handleContactDispatch(event)" class="space-y-3">
                            <div>
                                <label class="block text-[10px] uppercase font-mono text-slate-400 mb-1">Your Name</label>
                                <input type="text" id="contactName" required placeholder="Full Name" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-lg px-3 py-2 text-xs text-white focus:border-cyan-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-mono text-slate-400 mb-1">Your Email</label>
                                <input type="email" id="contactEmail" required placeholder="you@example.com" class="w-full bg-slate-900/90 border border-slate-700/80 rounded-lg px-3 py-2 text-xs text-white focus:border-cyan-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-mono text-slate-400 mb-1">Enquiry / Message</label>
                                <textarea id="contactMessage" required rows="3" placeholder="Describe your enquiry..." class="w-full bg-slate-900/90 border border-slate-700/80 rounded-lg px-3 py-2 text-xs text-white focus:border-cyan-400 focus:outline-none"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold py-2.5 rounded-lg text-xs uppercase tracking-wider transition">
                                Dispatch Enquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-800/80 py-8 bg-slate-950/80 text-xs text-slate-500 text-center">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div><span class="font-bold text-slate-300">SkillSync</span> • Autonomous Career Accelerator (TYBBA-CA CEP 2026)</div>
            <div class="text-slate-400">Authorized by Yogesh Annasaheb Shinde & Aryan Arjun Kadam</div>
        </div>
    </footer>

    <!-- Three.js 3D Interactive Particles -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        const canvas = document.getElementById('hero-canvas');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);

        const particleCount = 120;
        const geometry = new THREE.BufferGeometry();
        const positions = new Float32Array(particleCount * 3);

        for (let i = 0; i < particleCount * 3; i++) {
            positions[i] = (Math.random() - 0.5) * 12;
        }

        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        const material = new THREE.PointsMaterial({ color: 0x38bdf8, size: 0.035, transparent: true, opacity: 0.6 });
        const particleMesh = new THREE.Points(geometry, material);
        scene.add(particleMesh);
        camera.position.z = 4.5;

        let mouseX = 0, mouseY = 0;
        window.addEventListener('mousemove', (e) => {
            mouseX = (e.clientX / window.innerWidth - 0.5) * 0.4;
            mouseY = (e.clientY / window.innerHeight - 0.5) * 0.4;
        });

        function animate() {
            requestAnimationFrame(animate);
            particleMesh.rotation.y += 0.0012;
            particleMesh.rotation.x += 0.0006;
            camera.position.x += (mouseX - camera.position.x) * 0.05;
            camera.position.y += (-mouseY - camera.position.y) * 0.05;
            camera.lookAt(scene.position);
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // Copy Share Link Helper
        function copyShareUrl() {
            const copyInput = document.getElementById('portalShareLink');
            copyInput.select();
            copyInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyInput.value);

            const msg = document.getElementById('copySuccessMsg');
            msg.classList.remove('hidden');
            setTimeout(() => {
                msg.classList.add('hidden');
            }, 3500);
        }

        // Contact Dispatch Helper
        function handleContactDispatch(e) {
            e.preventDefault();
            const alertBox = document.getElementById('contactSentFeedback');
            alertBox.classList.remove('hidden');
            e.target.reset();
            setTimeout(() => {
                alertBox.classList.add('hidden');
            }, 5000);
        }
    </script>
</body>
<!-- ================= FLOATING SKILLSYNC AI ASSISTANT ================= -->
    <div id="aiChatWidget" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        
        <!-- Chat Pop-up Window -->
        <div id="aiChatWindow" class="hidden mb-4 w-80 sm:w-96 glass-panel border border-cyan-500/40 rounded-3xl shadow-2xl overflow-hidden flex flex-col transition-all duration-300">
            
            <!-- Header (Removed Gemini name) -->
            <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-cyan-950 p-4 border-b border-slate-800 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500 text-slate-950 flex items-center justify-center font-black text-sm">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white tracking-wider uppercase">SkillSync AI Assistant</h4>
                        <div class="flex items-center gap-1.5 text-[10px] text-emerald-400 font-mono">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Autonomous Engine Active
                        </div>
                    </div>
                </div>
                <button onclick="toggleAiChat()" class="text-slate-400 hover:text-white text-sm p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Messages Stream Area (Pure English) -->
            <div id="aiChatMessages" class="p-4 h-72 overflow-y-auto space-y-3 text-xs bg-slate-950/80">
                <div class="flex items-start gap-2">
                    <div class="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div class="bg-slate-900 border border-slate-800 p-3 rounded-2xl rounded-tl-none text-slate-200 leading-relaxed font-light">
                        Hello! I am the <strong>SkillSync AI Career Assistant</strong>. You can ask me about candidate skill tests, internships, certifications, platform architecture, or founders. How can I assist you today?
                    </div>
                </div>
            </div>

            <!-- Input Area (Pure English) -->
            <form onsubmit="handleAiSendMessage(event)" class="p-3 bg-slate-900 border-t border-slate-800 flex gap-2">
                <input type="text" id="aiUserInput" required placeholder="Type your query (e.g. How to apply?)..." class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-500 font-light">
                <button type="submit" id="aiSendBtn" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold px-4 py-2 rounded-xl text-xs transition flex items-center justify-center">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>

        <!-- Floating Trigger Button -->
        <button onclick="toggleAiChat()" class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-600 to-cyan-400 text-slate-950 flex items-center justify-center text-2xl shadow-xl shadow-cyan-500/30 hover:scale-105 transition-transform cursor-pointer">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </button>
    </div>

    <!-- AI Chat JavaScript Logic -->
    <script>
        function toggleAiChat() {
            const win = document.getElementById('aiChatWindow');
            win.classList.toggle('hidden');
            if (!win.classList.contains('hidden')) {
                document.getElementById('aiUserInput').focus();
            }
        }

        async function handleAiSendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('aiUserInput');
            const container = document.getElementById('aiChatMessages');
            const msg = input.value.trim();
            if (!msg) return;

            // 1. Show user message
            container.innerHTML += `
                <div class="flex items-start justify-end gap-2">
                    <div class="bg-cyan-500 text-slate-950 font-semibold p-3 rounded-2xl rounded-tr-none max-w-[80%] leading-relaxed">
                        ${escapeHtml(msg)}
                    </div>
                </div>
            `;
            input.value = '';
            container.scrollTop = container.scrollHeight;

            // 2. Typing Loader (English)
            const loaderId = 'loader_' + Date.now();
            container.innerHTML += `
                <div id="${loaderId}" class="flex items-start gap-2">
                    <div class="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div class="bg-slate-900 border border-slate-800 p-2.5 rounded-2xl rounded-tl-none text-slate-400 italic text-[11px]">
                        Analyzing query...
                    </div>
                </div>
            `;
            container.scrollTop = container.scrollHeight;

            try {
                const response = await fetch('api_chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: msg })
                });
                const data = await response.json();
                
                document.getElementById(loaderId).remove();
                container.innerHTML += `
                    <div class="flex items-start gap-2">
                        <div class="w-6 h-6 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <div class="bg-slate-900 border border-slate-800 p-3 rounded-2xl rounded-tl-none text-slate-200 leading-relaxed font-light">
                            ${formatAiResponse(data.reply)}
                        </div>
                    </div>
                `;
            } catch (err) {
                document.getElementById(loaderId).remove();
                container.innerHTML += `
                    <div class="text-[11px] text-red-400 p-2">Connection error. Please try again.</div>
                `;
            }
            container.scrollTop = container.scrollHeight;
        }

        function escapeHtml(text) {
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }

        function formatAiResponse(text) {
            return text.replace(/\n/g, '<br>');
        }
    </script>