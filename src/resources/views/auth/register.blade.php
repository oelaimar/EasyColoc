<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Create your account</h2>
        <p class="text-slate-500 mt-1">Start managing your shared living for free.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div class="space-y-1.5">
            <label for="name" class="text-sm font-semibold text-slate-700">Full Name</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">person</span>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-900 placeholder:text-slate-400"
                    placeholder="Alex Johnson" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="text-red-500 text-xs" />
        </div>

        {{-- Email --}}
        <div class="space-y-1.5">
            <label for="email" class="text-sm font-semibold text-slate-700">Email Address</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-900 placeholder:text-slate-400"
                    placeholder="you@example.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="text-red-500 text-xs" />
        </div>

        {{-- Password --}}
        <div class="space-y-1.5">
            <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-900 placeholder:text-slate-400"
                    placeholder="Min. 8 characters" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="text-red-500 text-xs" />
        </div>

        {{-- Confirm Password --}}
        <div class="space-y-1.5">
            <label for="password_confirmation" class="text-sm font-semibold text-slate-700">Confirm Password</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock_reset</span>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-sm text-slate-900 placeholder:text-slate-400"
                    placeholder="Repeat your password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="text-red-500 text-xs" />
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="w-full py-3.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:bg-primary/90 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[20px]">rocket_launch</span>
            Create Account
        </button>

        {{-- Login Link --}}
        <p class="text-center text-sm text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Sign in instead</a>
        </p>
    </form>
</x-guest-layout>