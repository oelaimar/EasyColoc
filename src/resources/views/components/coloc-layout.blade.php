<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EasyColoc') }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
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

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen">
    <div class="layout-container flex h-full grow flex-col min-h-screen">

        <!-- Header / Navigation -->
        <header class="flex items-center justify-between whitespace-nowrap border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 md:px-10 py-3 sticky top-0 z-50 shadow-sm" x-data="{ mobileOpen: false }">
            <div class="flex items-center gap-4 text-primary">
                <div class="size-9 flex items-center justify-center bg-primary rounded-lg text-white">
                    <span class="material-symbols-outlined">home_work</span>
                </div>
                <h2 class="text-slate-900 dark:text-slate-100 text-lg font-bold leading-tight tracking-[-0.015em]">
                    {{ auth()->user()->currentColocation->name ?? config('app.name') }}
                </h2>
            </div>

            <!-- Desktop Nav -->
            <div class="flex flex-1 justify-end gap-8 items-center">
                <nav class="hidden md:flex items-center gap-6">
                    <a class="{{ request()->routeIs('dashboard') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-slate-600 dark:text-slate-400 hover:text-primary transition-colors' }} text-sm"
                        href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="{{ request()->routeIs('expenses.*') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-slate-600 dark:text-slate-400 hover:text-primary transition-colors' }} text-sm"
                        href="{{ route('expenses.index') }}">Expenses</a>
                    <a class="{{ request()->routeIs('payments.*') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-slate-600 dark:text-slate-400 hover:text-primary transition-colors' }} text-sm"
                        href="{{ route('payments.index') }}">Settlements</a>
                    <a class="{{ request()->routeIs('colocations.invite') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-slate-600 dark:text-slate-400 hover:text-primary transition-colors' }} text-sm"
                        href="{{ route('colocations.invite') }}">Members</a>
                    <a class="{{ request()->routeIs('categories.*') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-slate-600 dark:text-slate-400 hover:text-primary transition-colors' }} text-sm"
                        href="{{ route('categories.index') }}">Categories</a>
                    @can('admin-only')
                    <a class="{{ request()->is('admin/*') ? 'text-primary font-bold border-b-2 border-primary pb-0.5' : 'text-slate-600 dark:text-slate-400 hover:text-primary transition-colors' }} text-sm"
                        href="{{ url('/admin/users') }}">Admin</a>
                    @endcan
                </nav>

                <!-- User Menu -->
                <div class="flex items-center gap-3" x-data="{ open: false }">
                    <!-- Reputation badge -->
                    @php $rep = auth()->user()->reputation_score ?? 0; @endphp
                    <div title="Reputation score" class="hidden sm:flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold
                        {{ $rep >= 0 ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' }}">
                        <span class="material-symbols-outlined text-sm leading-none">{{ $rep >= 0 ? 'thumb_up' : 'thumb_down' }}</span>
                        {{ $rep >= 0 ? '+' : '' }}{{ $rep }}
                    </div>

                    <!-- Avatar dropdown -->
                    <div class="relative">
                        <button @click="open = !open" class="focus:outline-none">
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 ring-2 ring-primary/20 hover:ring-primary/50 transition-all"
                                style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=137fec&color=fff");'>
                            </div>
                        </button>
                        <div x-show="open" @click.outside="open = false" style="display:none;"
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-800 py-1 z-50"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined text-lg">manage_accounts</span>
                                Profile Settings
                            </a>
                            <div class="border-t border-slate-100 dark:border-slate-800 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                        <span class="material-symbols-outlined text-lg">logout</span>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden text-slate-600 dark:text-slate-300 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                </div>
            </div>

            <!-- Mobile Nav Drawer -->
            <div x-show="mobileOpen" @click.outside="mobileOpen = false" style="display:none;"
                class="md:hidden absolute top-full left-0 right-0 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-lg z-40 py-3 px-6 flex flex-col gap-1"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0">
                <a href="{{ route('dashboard') }}" class="py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-primary border-b border-slate-100 dark:border-slate-800">Dashboard</a>
                <a href="{{ route('expenses.index') }}" class="py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-primary border-b border-slate-100 dark:border-slate-800">Expenses</a>
                <a href="{{ route('payments.index') }}" class="py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-primary border-b border-slate-100 dark:border-slate-800">Settlements</a>
                <a href="{{ route('colocations.invite') }}" class="py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-primary border-b border-slate-100 dark:border-slate-800">Members</a>
                <a href="{{ route('categories.index') }}" class="py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-primary border-b border-slate-100 dark:border-slate-800">Categories</a>
                @can('admin-only')
                <a href="{{ url('/admin/users') }}" class="py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-primary border-b border-slate-100 dark:border-slate-800">Admin</a>
                @endcan
            </div>
        </header>

        <main class="flex flex-1 justify-center py-8">
            {{ $slot }}
        </main>
    </div>
</body>

</html>