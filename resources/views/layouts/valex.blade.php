<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'JMA Kitchen - Restaurant Ordering System' }}</title>

    <!-- Google Fonts (Poppins & Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Local Compiled Tailwind CSS (Offline-Safe) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        valex: {
                            primary: '#6c5ffc',
                            hover: '#5646fc',
                            dark: '#141b2d',
                            sidebar: '#191e3a',
                            border: '#e8ebf3',
                            card: '#ffffff'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .valex-gradient { background: linear-gradient(135deg, #6c5ffc 0%, #05c3fb 100%); }
        .valex-card { background: #ffffff; border-radius: 0.75rem; border: 1px solid #e8ebf3; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: #fff !important; }
        }
    </style>
</head>
<body class="h-full text-slate-700 bg-slate-50 antialiased flex flex-col">

    <div class="flex h-screen overflow-hidden">

        <!-- ============================================================== -->
        <!-- VALEX SIDEBAR                                                  -->
        <!-- ============================================================== -->
        <aside class="no-print w-64 bg-[#141b2d] text-slate-300 shrink-0 flex flex-col justify-between transition-all duration-300">
            <div>
                <!-- Brand Logo -->
                <div class="h-16 flex items-center px-6 bg-[#0f1523] border-b border-slate-800">
                    <div class="w-9 h-9 rounded-lg valex-gradient flex items-center justify-center text-white font-bold text-lg shadow-md mr-3">
                        JMA
                    </div>
                    <div>
                        <span class="text-white font-bold text-lg tracking-wide">JMA KITCHEN</span>
                        <span class="text-[10px] block text-purple-400 uppercase tracking-widest font-semibold">Restaurant POS</span>
                    </div>
                </div>

                <!-- Navigation List -->
                <div class="px-4 py-6 overflow-y-auto max-h-[calc(100vh-140px)] space-y-1 text-sm">
                    <p class="px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Main Menu</p>

                    <!-- 1. Executive Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'bg-[#6c5ffc] text-white font-medium shadow-md shadow-purple-900/40' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                        <i class="bi bi-grid-1x2-fill text-lg mr-3"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- POS Order Terminal -->
                    <a href="{{ route('orders.pos') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('orders.pos') ? 'bg-[#6c5ffc] text-white font-medium shadow-md shadow-purple-900/40' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                        <i class="bi bi-bag-plus-fill text-lg mr-3"></i>
                        <span>POS Terminal</span>
                    </a>

                    <p class="px-3 text-[11px] font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2">Assigned Modules</p>

                    <!-- 2. Kitchen Status Board -->
                    <a href="{{ route('orders.status-board') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('orders.status-board') ? 'bg-[#6c5ffc] text-white font-medium shadow-md shadow-purple-900/40' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                        <i class="bi bi-kanban-fill text-lg mr-3"></i>
                        <span>Order Status Board</span>
                    </a>

                    <!-- 3. Sales Summary Report -->
                    <a href="{{ route('reports.sales') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('reports.sales') ? 'bg-[#6c5ffc] text-white font-medium shadow-md shadow-purple-900/40' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                        <i class="bi bi-graph-up-arrow text-lg mr-3"></i>
                        <span>Sales Summary Report</span>
                    </a>

                    <!-- 4. Top-Selling Menu Items -->
                    <a href="{{ route('reports.top-selling') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('reports.top-selling') ? 'bg-[#6c5ffc] text-white font-medium shadow-md shadow-purple-900/40' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                        <i class="bi bi-trophy-fill text-lg mr-3"></i>
                        <span>Top Selling Items</span>
                    </a>

                    <!-- 5. System Audit Trail -->
                    <a href="{{ route('audit.index') }}" 
                       class="flex items-center px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('audit.index') ? 'bg-[#6c5ffc] text-white font-medium shadow-md shadow-purple-900/40' : 'hover:bg-slate-800 hover:text-white text-slate-300' }}">
                        <i class="bi bi-shield-lock-fill text-lg mr-3"></i>
                        <span>Audit Trail</span>
                    </a>
                </div>
            </div>

            <!-- User Info & Sign Out Footer -->
            <div class="p-4 bg-[#0f1523] border-t border-slate-800 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-purple-600/30 text-purple-300 flex items-center justify-center font-bold text-xs uppercase">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-white truncate w-28">{{ Auth::user()->name ?? 'Staff User' }}</p>
                        <p class="text-[10px] text-emerald-400 capitalize">{{ Auth::user()->role ?? 'Online' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="text-slate-400 hover:text-rose-400 transition">
                        <i class="bi bi-box-arrow-right text-lg"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ============================================================== -->
        <!-- MAIN CONTENT AREA                                              -->
        <!-- ============================================================== -->
        <div class="flex-1 flex flex-col overflow-hidden bg-slate-50">

            <!-- VALEX TOP HEADER -->
            <header class="no-print h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shadow-sm z-10">
                <!-- Search bar -->
                <div class="flex items-center flex-1 max-w-md">
                    <div class="relative w-full">
                        <i class="bi bi-search absolute left-3.5 top-2.5 text-slate-400 text-sm"></i>
                        <input type="text" placeholder="Search orders, dishes, receipts..." 
                               class="w-full bg-slate-50 border border-slate-200 text-sm rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:border-[#6c5ffc] focus:bg-white transition">
                    </div>
                </div>

                <!-- Right Header Elements -->
                <div class="flex items-center space-x-4">
                    <!-- Date badge -->
                    <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="bi bi-calendar3 mr-1.5 text-[#6c5ffc]"></i>
                        {{ date('M d, Y') }}
                    </span>

                    <!-- Quick Notification Icon -->
                    <div class="relative cursor-pointer text-slate-500 hover:text-slate-700">
                        <i class="bi bi-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-rose-500 rounded-full"></span>
                    </div>

                    <!-- Role Badge -->
                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-purple-50 text-[#6c5ffc] border border-purple-100 uppercase tracking-wider">
                        {{ Auth::user()->role ?? 'Staff' }}
                    </span>
                </div>
            </header>

            <!-- FLASH ALERTS -->
            <div class="no-print px-8 pt-4">
                @if(session('success'))
                    <div class="p-3 mb-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center shadow-sm">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-base mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="p-3 mb-2 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill text-rose-500 text-base mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- SCROLLABLE PAGE BODY -->
            <main class="flex-1 overflow-y-auto px-8 py-6">
                @yield('content')
            </main>

            <!-- VALEX FOOTER -->
            <footer class="no-print bg-white border-t border-slate-200 py-3 px-8 text-center text-xs text-slate-400">
                Copyright © {{ date('Y') }} <span class="font-semibold text-slate-600">JMA Kitchen</span> — Designed for Group 08 OOP Project.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>