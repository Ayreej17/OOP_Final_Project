<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JMA Kitchen — Restaurant POS &amp; Management System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/jma-logo.png') }}">

    <!-- Fonts & Icons (matching Valex Dashboard) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f1523;
            background-image:
                radial-gradient(circle at 15% 15%, rgba(99, 102, 241, 0.18) 0%, transparent 45%),
                radial-gradient(circle at 85% 85%, rgba(139, 92, 246, 0.16) 0%, transparent 45%);
        }
        .valex-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        .valex-gradient:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-between text-slate-200 antialiased">

    <!-- Top Navigation Bar -->
    <header class="w-full border-b border-slate-800/80 bg-[#0f1523]/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-6 h-18 py-4 flex items-center justify-between">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div style="width: 42px; height: 42px;" class="rounded-xl overflow-hidden ring-2 ring-purple-500/30 shadow-md shrink-0">
                    <img src="{{ asset('images/jma-logo.png') }}" alt="JMA Kitchen Logo" class="w-full h-full object-cover">
                </div>
                <div class="leading-tight">
                    <span class="text-white font-extrabold text-base tracking-wide block">JMA KITCHEN</span>
                    <span class="text-[10px] text-purple-400 uppercase tracking-widest font-semibold block">Restaurant POS</span>
                </div>
            </a>

            <!-- Auth Navigation -->
            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-4 py-2 rounded-xl valex-gradient text-white text-xs font-semibold shadow-lg shadow-purple-500/25 transition flex items-center gap-2">
                            <i class="bi bi-speedometer2"></i>
                            <span>Go to Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 rounded-xl valex-gradient text-white text-xs font-semibold shadow-lg shadow-purple-500/25 transition flex items-center gap-1.5">
                            <span>Sign In</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 rounded-xl !bg-[#1a233a] hover:!bg-slate-800 border border-slate-600 !text-white text-xs font-semibold transition">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Main Hero & System Highlights -->
    <main class="max-w-6xl mx-auto px-6 py-14 flex-1 flex flex-col justify-center">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

            <!-- Left Column: Hero Copy -->
            <div class="lg:col-span-6 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-500/10 border border-purple-500/25 text-purple-300 text-xs font-semibold">
                    <i class="bi bi-stars"></i>
                    <span>Object-Oriented Programming &bull; Group 08</span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight">
                    Freshly Cooked, Seamlessly Served at
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">
                        JMA Kitchen
                    </span>
                </h1>

                <p class="text-sm sm:text-base text-slate-400 leading-relaxed max-w-xl">
                    Welcome to the JMA Kitchen management portal—powering fast counter service, real-time kitchen coordination, and smooth daily dining operations.
                </p>

                <!-- Primary Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="px-6 py-3 rounded-xl valex-gradient text-white font-semibold text-sm shadow-xl shadow-purple-500/25 transition flex items-center gap-2">
                                <span>Open Executive Dashboard</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="px-6 py-3 rounded-xl valex-gradient text-white font-semibold text-sm shadow-xl shadow-purple-500/25 transition flex items-center gap-2">
                                <span>Launch POS Terminal</span>
                                <i class="bi bi-box-arrow-in-right"></i>
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="px-6 py-3 rounded-xl !bg-[#1a233a] hover:!bg-slate-800 border border-slate-600 !text-white font-semibold text-sm transition">
                                    Create Staff Account
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>

            <!-- Right Column: Brand Card & Core Modules -->
            <div class="lg:col-span-6">
                <div class="bg-[#141b2d]/90 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl shadow-black/40">
                    <!-- Brand Showcase -->
                    <div class="flex items-center gap-4 pb-6 mb-6 border-b border-slate-800">
                        <div style="width: 68px; height: 68px;" class="rounded-2xl overflow-hidden ring-4 ring-purple-500/25 shadow-lg shrink-0">
                            <img src="{{ asset('images/jma-logo.png') }}" alt="JMA Kitchen Chef Cat" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h2 class="text-white font-bold text-lg">JMA</h2>
                        </div>
                    </div>

                    <!-- 4 Core Modules Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-[#0f1523]/80 border border-slate-800/90">
                            <div class="w-9 h-9 rounded-lg bg-indigo-500/15 text-indigo-400 flex items-center justify-center text-base mb-2.5">
                                <i class="bi bi-calculator"></i>
                            </div>
                            <h3 class="text-white font-semibold text-sm">POS Order Terminal</h3>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                Fast item selection, discount rules, and instant stock deduction.
                            </p>
                        </div>

                        <div class="p-4 rounded-xl bg-[#0f1523]/80 border border-slate-800/90">
                            <div class="w-9 h-9 rounded-lg bg-amber-500/15 text-amber-400 flex items-center justify-center text-base mb-2.5">
                                <i class="bi bi-kanban"></i>
                            </div>
                            <h3 class="text-white font-semibold text-sm">Kitchen Status Board</h3>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                Live pending-to-completed order tracking for kitchen staff.
                            </p>
                        </div>

                        <div class="p-4 rounded-xl bg-[#0f1523]/80 border border-slate-800/90">
                            <div class="w-9 h-9 rounded-lg bg-purple-500/15 text-purple-400 flex items-center justify-center text-base mb-2.5">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h3 class="text-white font-semibold text-sm">Sales &amp; Menu Control</h3>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                Real-time revenue summaries, top-selling dishes, and inventory updates.
                            </p>
                        </div>

                        <div class="p-4 rounded-xl bg-[#0f1523]/80 border border-slate-800/90">
                            <div class="w-9 h-9 rounded-lg bg-emerald-500/15 text-emerald-400 flex items-center justify-center text-base mb-2.5">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h3 class="text-white font-semibold text-sm">RBAC &amp; Audit Trail</h3>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                Role-separated Admin/Staff permissions with full action logging.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 py-5 text-center text-xs text-slate-500">
        Copyright &copy; {{ date('Y') }} <span class="text-slate-300 font-semibold">JMA Kitchen</span> &mdash; Designed for Group 08 OOP Project.
    </footer>

</body>

</html>
