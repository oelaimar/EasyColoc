<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Welcome back</h2>
        <p class="text-slate-500 mt-1">Sign in to manage your colocation.</p>
    </div>

    <x-auth-session-status class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm" :status="session('status')" />

    @if (session('error'))
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div class="space-y-1.5">
            <label for="email" class="text-sm font-semibold text-slate-700">Email Address</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-900 placeholder:text-slate-400"
                    placeholder="you@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="text-red-500 text-xs" />
        </div>

        {{-- Password --}}
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-primary font-semibold hover:underline">Forgot password?</a>
                @endif
            </div>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-900 placeholder:text-slate-400"
                    placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="text-red-500 text-xs" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center gap-2">
            <input id="remember_me" name="remember" type="checkbox"
                class="rounded border-slate-300 text-primary shadow-sm focus:ring-primary/30 size-4">
            <label for="remember_me" class="text-sm text-slate-600">Keep me signed in</label>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full py-3.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[20px]">login</span>
            Sign In
        </button>

        {{-- Register Link --}}
        <p class="text-center text-sm text-slate-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Create one free</a>
        </p>
    </form>
</x-guest-layout>