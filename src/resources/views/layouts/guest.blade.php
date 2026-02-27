<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EasyColoc') }}</title>

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
                        "background-light": "#f6f7f8"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
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

        .gradient-bg {
            background: linear-gradient(135deg, #137fec 0%, #5b3ff8 100%);
        }
    </style>
</head>

<body class="bg-background-light text-slate-900 min-h-screen font-display antialiased">
    <div class="min-h-screen grid md:grid-cols-2">

        {{-- Left decorative panel --}}
        <div class="hidden md:flex gradient-bg flex-col items-center justify-center px-12 relative overflow-hidden">
            {{-- Decorative blobs --}}
            <div class="absolute top-0 left-0 size-96 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 size-80 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"></div>

            <div class="relative z-10 text-white text-center max-w-sm">
                <div class="size-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm border border-white/20">
                    <span class="material-symbols-outlined text-3xl">home_work</span>
                </div>
                <h1 class="text-3xl font-black tracking-tight mb-3">{{ config('app.name') }}</h1>
                <p class="text-white/70 text-base leading-relaxed mb-10">
                    The smart way to manage your shared living — split costs, track debts, live in harmony.
                </p>
                {{-- Mini feature bullets --}}
                <div class="space-y-3 text-left">
                    @foreach(['Split expenses fairly', 'Settle debts one click', 'Invite roommates instantly'] as $f)
                    <div class="flex items-center gap-3">
                        <div class="size-7 shrink-0 rounded-full bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[14px]">check</span>
                        </div>
                        <span class="text-sm font-medium text-white/90">{{ $f }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right form panel --}}
        <div class="flex flex-col items-center justify-center px-6 py-12">
            {{-- Mobile logo --}}
            <div class="flex items-center gap-2 mb-10 md:hidden">
                <div class="size-9 bg-primary rounded-xl flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-[20px]">home_work</span>
                </div>
                <span class="text-lg font-black text-slate-900">{{ config('app.name') }}</span>
            </div>

            <div class="w-full max-w-md">
                {{ $slot }}
            </div>

            <p class="mt-8 text-xs text-slate-400 text-center">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>