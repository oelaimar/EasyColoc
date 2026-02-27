<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Get Started | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen">
    <div class="relative flex min-h-screen flex-col">

        {{-- Top Navigation --}}
        <header class="sticky top-0 z-50 w-full border-b border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-background-dark/90 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center justify-center size-9 bg-primary rounded-lg text-white">
                            <span class="material-symbols-outlined">home_work</span>
                        </div>
                        <h2 class="text-slate-900 dark:text-white text-xl font-bold tracking-tight">{{ config('app.name') }}</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        @can('admin-only')
                        <a href="{{ url('/admin/users') }}"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-sm font-semibold hover:bg-amber-200 dark:hover:bg-amber-900/40 transition-colors">
                            <span class="material-symbols-outlined text-base leading-none">admin_panel_settings</span>
                            Admin
                        </a>
                        @endcan
                        <span class="text-sm text-slate-500 dark:text-slate-400">Logged in as <strong>{{ auth()->user()->name }}</strong></span>
                        <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-800"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-slate-500 hover:text-red-500 flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-lg">logout</span> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 flex flex-col max-w-5xl mx-auto w-full px-4 py-8 md:py-12">

            @if(session('success'))
            <div class="mb-8 p-4 rounded-xl border border-green-200 bg-green-50 dark:bg-green-950/20 dark:border-green-900/30 text-green-800 dark:text-green-300 flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-8 p-4 rounded-xl border border-red-200 bg-red-50 dark:bg-red-950/20 dark:border-red-900/30 text-red-800 dark:text-red-300">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Welcome Title --}}
            <div class="mb-12 text-center md:text-left">
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-4">Getting Started</h1>
                <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl">Welcome! To begin managing shared expenses, you'll need to either create a new colocation or join an existing one.</p>
            </div>

            {{-- Action Cards --}}
            <div class="grid md:grid-cols-2 gap-8 mb-12">

                {{-- Create Colocation Card --}}
                <div class="group flex flex-col bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 p-8">
                    <div class="mb-8">
                        <div class="inline-flex items-center justify-center size-14 rounded-2xl bg-primary/10 text-primary mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">add_home</span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Start a Colocation</h2>
                        <p class="text-slate-500 dark:text-slate-400">Set up a new shared living space and invite your roommates to join you.</p>
                    </div>
                    <div class="mt-auto">
                        <form method="POST" action="{{ route('colocations.store') }}" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Colocation Name</label>
                                <input name="name" required
                                    class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all text-slate-900 dark:text-white placeholder:text-slate-400"
                                    placeholder="e.g. Sunset Heights Apartment" type="text" />
                            </div>
                            <button type="submit"
                                class="w-full py-4 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                                Create Colocation
                            </button>
                        </form>
                        <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                            <p class="text-xs text-slate-400">You'll become the owner and can invite roommates via a token.</p>
                        </div>
                    </div>
                </div>

                {{-- Join Colocation Card --}}
                <div class="group flex flex-col bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 p-8">
                    <div class="mb-8">
                        <div class="inline-flex items-center justify-center size-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">key</span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Join via Invitation</h2>
                        <p class="text-slate-500 dark:text-slate-400">Have a code from your roommates? Enter it here to join an existing group instantly.</p>
                    </div>
                    <div class="mt-auto">
                        <form method="POST" action="{{ route('memberships.join') }}" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Token / Invite Code</label>
                                <div class="relative">
                                    <input name="token" required
                                        class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all text-slate-900 dark:text-white placeholder:text-slate-400 pr-12"
                                        placeholder="e.g. ABC-123-XYZ" type="text" />
                                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">content_paste</span>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full py-4 bg-slate-900 dark:bg-slate-100 dark:text-slate-900 text-white font-bold rounded-xl shadow-lg shadow-slate-900/10 transition-all active:scale-[0.98] hover:bg-slate-800 dark:hover:bg-white">
                                Join Now
                            </button>
                        </form>
                        <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-slate-400 text-lg">info</span>
                            <p class="text-xs text-slate-400">Invites are usually sent via email or direct link from your roommates.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-auto py-8 text-center border-t border-slate-200 dark:border-slate-800">
                <p class="text-slate-500 dark:text-slate-400 text-sm">
                    Confused? <a class="text-primary font-semibold hover:underline" href="#">Read our onboarding guide</a>
                </p>
            </div>
        </main>
    </div>
</body>

</html>