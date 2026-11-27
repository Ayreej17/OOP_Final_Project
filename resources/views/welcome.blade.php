<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JMA Kitchen — Restaurant POS &amp; Management System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/jma-logo.png') }}">

    <!-- Fonts & Icons (matching Dashboard, Login & Register) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f1523;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(99, 102, 241, 0.16) 0%, transparent 45%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.14) 0%, transparent 45%);
        }
        .valex-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        .valex-gradient:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-between text-slate-200 antialiased selection:bg-purple-500/30 selection:text-purple-200">

    <!-- Minimal Top Navigation Bar -->
    <header class="w-full">
        <div class="max-w-6xl mx-auto px-6 py-6 flex items-center justify-between">
            <!-- Brand Logo + Name -->
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div style="width: 40px; height: 40px;" class="rounded-xl overflow-hidden ring-2 ring-purple-500/30 shadow-md shrink-0 bg-[#141b2d]">
                    <img src="{{ asset('images/jma-logo.png') }}" alt="JMA Kitchen Logo" class="w-full h-full object-cover">
                </div>
                <div class="leading-tight">
                    <span class="font-extrabold text-base tracking-wide text-white block">JMA KITCHEN</span>
                    <span class="text-[10px] text-purple-400 uppercase tracking-widest font-semibold block">Restaurant POS</span>
                </div>
            </a>

            <!-- Auth Actions Only -->
            @if (Route::has('login'))
                <nav class="flex items-center gap-4 text-xs sm:text-sm font-medium">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="px-4 py-2.5 rounded-xl valex-gradient !text-white text-xs font-semibold shadow-lg shadow-purple-500/25 transition">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white font-semibold transition">
                            Sign In
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-4 py-2.5 rounded-xl valex-gradient !text-white text-xs font-semibold shadow-lg shadow-purple-500/25 transition">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Main Hero Section -->
    <main class="max-w-6xl mx-auto px-6 py-10 lg:py-16 flex-1 flex items-center">
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

            <!-- Left Column: Clean Editorial Hero -->
            <div class="lg:col-span-6 space-y-6">
                <div class="flex items-center gap-2.5 text-[11px] font-bold uppercase tracking-widest text-purple-400">
                    <span class="w-5 h-[2px] bg-purple-400 inline-block"></span>
                    <span>Fast-Casual Dining &amp; Kitchen Operations</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-[3.4rem] font-extrabold tracking-tight leading-[1.1] text-white">
                    Freshly Cooked.
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400 mt-1">
                        Seamlessly Served.
                    </span>
                </h1>

                <p class="text-sm sm:text-base text-slate-400 leading-relaxed max-w-md">
                    Welcome to the <strong class="font-semibold text-slate-200">JMA Kitchen</strong> management portal—powering fast counter ordering, live kitchen ticket tracking, and smooth daily restaurant operations.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="px-5 py-3 rounded-xl valex-gradient !text-white text-xs sm:text-sm font-semibold shadow-lg shadow-purple-500/25 transition inline-flex items-center gap-2">
                                <span>Open Dashboard</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="px-5 py-3 rounded-xl valex-gradient !text-white text-xs sm:text-sm font-semibold shadow-lg shadow-purple-500/25 transition inline-flex items-center gap-2">
                                <span>Launch POS</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="px-5 py-3 rounded-xl !bg-[#1a233a] hover:!bg-slate-800 border border-slate-700 !text-white text-xs sm:text-sm font-semibold transition">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>

            <!-- Right Column: Layered Floating Card Stack (Matching Login/Register White Card on Dark Navy) -->
            <div class="lg:col-span-6 relative flex justify-center lg:justify-end">
                <!-- Decorative Subtle Background Card (Tilted) -->
                <div class="hidden sm:block absolute -top-4 right-2 w-full max-w-md h-80 bg-[#141b2d]/90 border border-slate-700/70 rounded-2xl rotate-3 shadow-xl pointer-events-none"></div>

                <!-- Decorative Purple Dot Accent -->
                <span class="hidden sm:block w-2.5 h-2.5 rounded-full bg-purple-400 absolute -left-2 top-1/3"></span>

                <!-- Main Foreground White Card (Matches Login & Register Card) -->
                <div class="relative w-full max-w-md bg-white border border-slate-100 rounded-2xl p-7 shadow-2xl shadow-black/40 text-slate-800">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-purple-600 block mb-1.5">
                                Built for Speed &amp; Accuracy
                            </span>
                            <h2 class="text-xl font-bold text-slate-900">
                                Run every shift smoothly
                            </h2>
                            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                                From counter checkout to kitchen prep and inventory tracking, keep your team synced in real time.
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 text-base">
                            <i class="bi bi-shop"></i>
                        </div>
                    </div>

                    <!-- Preview Rows -->
                    <div class="space-y-2.5 my-5 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between text-xs bg-slate-50 rounded-xl px-3.5 py-2.5 border border-slate-200/70">
                            <span class="font-semibold text-slate-700 flex items-center gap-2">
                                <i class="bi bi-calculator text-indigo-600"></i>
                                POS Counter Checkout
                            </span>
                            <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Auto-Stock</span>
                        </div>
                        <div class="flex items-center justify-between text-xs bg-slate-50 rounded-xl px-3.5 py-2.5 border border-slate-200/70">
                            <span class="font-semibold text-slate-700 flex items-center gap-2">
                                <i class="bi bi-kanban text-amber-500"></i>
                                Kitchen Order Board
                            </span>
                            <span class="text-[11px] font-semibold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md">Live Queue</span>
                        </div>
                    </div>

                    <!-- Bottom Check Pill -->
                    <div class="flex items-center gap-2.5 pt-1">
                        <span class="w-6 h-6 rounded-full valex-gradient text-white flex items-center justify-center text-xs shrink-0">
                            <i class="bi bi-check"></i>
                        </span>
                        <span class="text-xs font-medium text-slate-600">Dine-in &amp; take-out ready</span>
                    </div>

                    <!-- Overlapping Bottom-Right Mini Floating Card -->
                    <div class="hidden sm:flex items-center gap-3 bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-xl shadow-black/25 absolute -bottom-5 -right-5">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm shrink-0">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs font-bold text-slate-800">Role-Secured</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Admin &amp; Staff RBAC</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Minimal Footer -->
    <footer class="max-w-6xl mx-auto w-full px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} <span class="font-semibold text-slate-400">JMA Kitchen</span>. All rights reserved.</p>
        <p>Group 08 &mdash; Object-Oriented Programming</p>
    </footer>

</body>

</html>
