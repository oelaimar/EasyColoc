<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Global Admin User Management | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
                        "display": ["Inter"]
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
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
    <div class="flex min-h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 border-r border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark flex flex-col shrink-0">
            <div class="p-6 flex items-center gap-3">
                <div class="bg-primary p-2 rounded-lg">
                    <span class="material-symbols-outlined text-white">apartment</span>
                </div>
                <div>
                    <h1 class="text-lg font-bold leading-none">{{ config('app.name') }}</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Global Admin</p>
                </div>
            </div>
            <nav class="flex-1 px-4 py-4 space-y-1">
                <a class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined text-xl">arrow_back</span>
                    <span class="text-sm font-medium">Back to App</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-primary/10 text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined text-xl">group</span>
                    <span class="text-sm font-medium">User Management</span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="size-9 rounded-full bg-slate-200 dark:bg-slate-700 bg-cover bg-center"
                        style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}')">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Top Header -->
            <header class="h-16 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark px-8 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-4 flex-1">
                    <h2 class="text-xl font-bold">Admin Dashboard</h2>
                </div>
            </header>

            <div class="p-8">
                <!-- KPI Cards -->
                {{-- [FIX] Variables are now passed from AdminUserController::index() — no more inline queries in the view --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-primary/10 p-2 rounded-lg text-primary">
                                <span class="material-symbols-outlined">group</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Users</p>
                        <h3 class="text-2xl font-bold mt-1">{{ number_format($totalUsersCount) }}</h3>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-primary/10 p-2 rounded-lg text-primary">
                                <span class="material-symbols-outlined">meeting_room</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Active Colocations</p>
                        <h3 class="text-2xl font-bold mt-1">{{ number_format($totalColocationsCount) }}</h3>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-primary/10 p-2 rounded-lg text-primary">
                                <span class="material-symbols-outlined">payments</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Expenses</p>
                        <h3 class="text-2xl font-bold mt-1">{{ number_format($totalExpensesSum, 2) }}€</h3>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-red-500/10 p-2 rounded-lg text-red-500">
                                <span class="material-symbols-outlined">block</span>
                            </div>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Banned Users</p>
                        <h3 class="text-2xl font-bold mt-1">{{ number_format($bannedUsersCount) }}</h3>
                    </div>
                </div>

                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-600 rounded-xl">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Main Content Table Card -->
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold">User Management</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Manage global users and account statuses.</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs uppercase font-bold tracking-wider">
                                    <th class="px-6 py-4">User</th>
                                    <th class="px-6 py-4">Verification</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($users as $user)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="size-10 rounded-lg bg-primary/20 flex items-center justify-center text-primary font-bold overflow-hidden">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_global_admin)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                            Global Admin
                                        </span>
                                        @else
                                        <span class="text-xs font-normal text-slate-500">User</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if(!$user->is_banned)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-600">
                                            <span class="size-1.5 rounded-full bg-green-600"></span> Active
                                        </span>
                                        @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-600">
                                            <span class="size-1.5 rounded-full bg-red-600"></span> Banned
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.user.toggleBan', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @if(!$user->is_banned)
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-500/10 text-red-600 hover:bg-red-500 hover:text-white transition-all">Ban User</button>
                                            @else
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all">Unban User</button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-slate-500">No users found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>