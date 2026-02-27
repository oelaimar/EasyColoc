<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile & Settings | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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

        .active-nav {
            background-color: rgba(19, 127, 236, 0.1);
            color: #137fec;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 transition-colors duration-200">
    <div class="flex min-h-screen">

        {{-- Side Navigation --}}
        <aside class="w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col fixed h-full z-20">
            <div class="p-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">home_work</span>
                </div>
                <div>
                    <h1 class="text-lg font-bold tracking-tight text-slate-900 dark:text-white leading-none">{{ config('app.name') }}</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Account Settings</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1">
                <div class="pb-2 px-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Navigation</div>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('expenses.index') }}">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span class="text-sm font-medium">Expenses</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="{{ route('payments.index') }}">
                    <span class="material-symbols-outlined">payments</span>
                    <span class="text-sm font-medium">Settlements</span>
                </a>
                <div class="pt-6 pb-2 px-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Settings</div>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg active-nav" href="{{ route('profile.edit') }}">
                    <span class="material-symbols-outlined text-primary">person</span>
                    <span class="text-sm font-medium">Public Profile</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">security</span>
                    <span class="text-sm font-medium">Security</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-3 p-2">
                    <div class="w-9 h-9 rounded-full overflow-hidden ring-2 ring-primary/20">
                        <img class="w-full h-full object-cover"
                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=137fec&color=fff" />
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <p class="text-sm font-semibold truncate">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors" title="Logout">
                            <span class="material-symbols-outlined text-lg">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="ml-72 flex-1 p-8 lg:p-12 overflow-y-auto">
            <div class="max-w-4xl mx-auto">
                <header class="mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Public Profile</h2>
                    <p class="text-slate-600 dark:text-slate-400 mt-1">Control how others see you on the platform.</p>
                </header>

                <div class="space-y-6">

                    {{-- Profile Info Card --}}
                    <section class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')

                            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center gap-6">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden ring-4 ring-white dark:ring-slate-800">
                                        <img class="w-full h-full object-cover"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=256&background=137fec&color=fff" />
                                    </div>
                                    <div class="absolute bottom-0 right-0 p-1.5 bg-primary text-white rounded-full border-2 border-white dark:border-slate-900">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">Profile Picture</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Auto-generated from your name.</p>
                                </div>
                            </div>

                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Full Name</label>
                                    <input name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm" />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-sm font-semibold text-slate-400 dark:text-slate-500">
                                        Email Address <span class="text-[10px] font-normal italic ml-1">(editable)</span>
                                    </label>
                                    <input name="email" value="{{ old('email', $user->email) }}" required type="email" autocomplete="username"
                                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm" />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <p class="text-sm mt-2 text-amber-600">
                                        Your email address is unverified.
                                        <button form="send-verification" class="underline font-semibold hover:text-amber-800 transition-colors">
                                            Re-send verification email.
                                        </button>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">Verification link sent!</p>
                                    @endif
                                    @endif
                                </div>
                            </div>

                            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/30 flex items-center justify-end gap-4">
                                @if (session('status') === 'profile-updated')
                                <p class="text-sm text-green-600 font-medium flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">check_circle</span> Saved successfully.
                                </p>
                                @endif
                                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-bold text-sm shadow-sm hover:bg-primary/90 transition-all">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>
                    </section>

                    {{-- Security Section --}}
                    <section class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                            <h3 class="text-lg font-bold">Security</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Manage your password and account protection.</p>
                        </div>
                        <div class="p-6">
                            <form method="post" action="{{ route('password.update') }}">
                                @csrf
                                @method('put')
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="md:col-span-1">
                                        <h4 class="text-sm font-bold mb-1">Change Password</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Update your password regularly to stay secure.</p>
                                    </div>
                                    <div class="md:col-span-2 space-y-4">
                                        <div>
                                            <input name="current_password" type="password" required autocomplete="current-password"
                                                class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm"
                                                placeholder="Current Password" />
                                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <input name="password" type="password" required autocomplete="new-password"
                                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm"
                                                    placeholder="New Password" />
                                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                            </div>
                                            <div>
                                                <input name="password_confirmation" type="password" required autocomplete="new-password"
                                                    class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm"
                                                    placeholder="Confirm Password" />
                                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <button type="submit" class="bg-slate-900 dark:bg-slate-100 dark:text-slate-900 text-white px-5 py-2 rounded-lg font-bold text-sm hover:bg-slate-700 dark:hover:bg-white transition-all">
                                                Update Password
                                            </button>
                                            @if (session('status') === 'password-updated')
                                            <p class="text-sm text-green-600 font-medium flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">check_circle</span> Saved.
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>

                    {{-- Danger Zone --}}
                    <section x-data="{ openDeleteModal: false }"
                        class="p-6 bg-red-50 dark:bg-red-900/10 rounded-xl border border-red-100 dark:border-red-900/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h4 class="text-sm font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">warning</span> Delete Account
                            </h4>
                            <p class="text-xs text-red-600/70 dark:text-red-400/70 mt-1">Permanently delete your account and all associated data. This cannot be undone.</p>
                        </div>
                        <button @click="openDeleteModal = true" class="px-5 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition-colors shrink-0">
                            Delete Account
                        </button>

                        {{-- Delete Confirmation Modal --}}
                        <div x-show="openDeleteModal" style="display:none;"
                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openDeleteModal = false"></div>
                            <div class="relative bg-white dark:bg-slate-900 rounded-2xl p-8 max-w-md w-full shadow-2xl">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Delete Account?</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                                    Once deleted, all your data will be permanently removed. Enter your password to confirm.
                                </p>
                                <form method="post" action="{{ route('profile.destroy') }}">
                                    @csrf
                                    @method('delete')
                                    <input type="password" name="password" required placeholder="Your password"
                                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none mb-4 text-sm">
                                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mb-4 text-red-600" />
                                    <div class="flex gap-3">
                                        <button type="submit" class="flex-1 py-2.5 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-colors text-sm">
                                            Yes, Delete
                                        </button>
                                        <button @click="openDeleteModal = false" type="button" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>

                <footer class="mt-12 py-6 text-center border-t border-slate-200 dark:border-slate-800">
                    <p class="text-xs text-slate-400">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </footer>
            </div>
        </main>
    </div>

    @if($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('modal', {
                open: true
            });
        });
    </script>
    @endif
</body>

</html>