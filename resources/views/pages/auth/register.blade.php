<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JMA Kitchen — Create Account</title>

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

<body class="min-h-screen flex flex-col items-center justify-center px-4 py-10 text-slate-800 antialiased">

    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="flex flex-col items-center text-center mb-7">
            <div style="width: 84px; height: 84px;" class="rounded-2xl overflow-hidden ring-4 ring-purple-500/30 shadow-xl shadow-indigo-950/60 mb-3 bg-[#141b2d]">
                <img src="{{ asset('images/jma-logo.png') }}"
                     alt="JMA Kitchen Logo"
                     class="w-full h-full object-cover">
            </div>
            <h1 class="text-white font-extrabold text-xl tracking-wide">JMA KITCHEN</h1>
            <p class="text-[11px] text-purple-400 uppercase tracking-widest font-semibold mt-0.5">
                Restaurant POS &amp; Management System
            </p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-2xl shadow-black/40 border border-slate-100 p-8">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-slate-800">Create an Account</h2>
                <p class="text-xs text-slate-500 mt-1">Register a new staff account for JMA Kitchen POS</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs flex items-start gap-2.5">
                    <i class="bi bi-exclamation-circle-fill text-sm mt-0.5 shrink-0"></i>
                    <div class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                @csrf

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Full Name
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               autofocus
                               autocomplete="name"
                               placeholder="Juan Dela Cruz"
                               class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 transition">
                    </div>
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Email Address
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email"
                               placeholder="staff@jmakitchen.com"
                               class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 transition">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="register-password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password"
                               id="register-password"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 transition">
                        <button type="button"
                                onclick="toggleField('register-password', 'icon-password')"
                                aria-label="Toggle password visibility"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition">
                            <i id="icon-password" class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="register-password-confirm" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input type="password"
                               id="register-password-confirm"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 transition">
                        <button type="button"
                                onclick="toggleField('register-password-confirm', 'icon-confirm')"
                                aria-label="Toggle confirm password visibility"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition">
                            <i id="icon-confirm" class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-2.5 px-4 rounded-xl valex-gradient text-white font-semibold text-sm shadow-lg shadow-purple-500/25 hover:shadow-purple-500/40 transition duration-150 flex items-center justify-center gap-2 cursor-pointer mt-2">
                    <span>Create Account</span>
                    <i class="bi bi-person-plus"></i>
                </button>
            </form>

            @if (Route::has('login'))
                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-purple-600 hover:text-purple-700 transition">
                            Sign In
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <p class="text-center text-[11px] text-slate-500 mt-6">
            &copy; {{ date('Y') }} <span class="text-slate-400 font-semibold">JMA Kitchen</span> &mdash; Group 08 OOP Project
        </p>
    </div>

    <script>
        function toggleField(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }
    </script>
</body>

</html>
