<x-coloc-layout>
    <div class="max-w-6xl mx-auto px-4 md:px-10 py-8 w-full">

        {{-- Header --}}
        <div class="flex flex-wrap justify-between items-end gap-4 mb-8">
            <div class="flex flex-col gap-1">
                <h1 class="text-slate-900 dark:text-slate-100 text-4xl font-black leading-tight tracking-[-0.033em]">Settlements & Payments</h1>
                <p class="text-slate-500 dark:text-slate-400 text-base font-normal leading-normal">Manage your debts and settle up with your roommates effortlessly.</p>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-6 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
        </div>
        @endif

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left: Pending Settlements --}}
            <div class="lg:col-span-2 flex flex-col gap-4">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-slate-900 dark:text-slate-100 text-[22px] font-bold leading-tight tracking-[-0.015em]">Pending Settlements</h2>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-full">
                        {{ count($credits) + count($debts) }} Items
                    </span>
                </div>

                @php
                $allPendingPayments = collect($credits)->merge($debts)->unique('id');
                @endphp

                @forelse($allPendingPayments as $p)
                @php
                $isCreditor = $p->creditor_id == auth()->id();
                $otherUser = $isCreditor ? $p->debtor : $p->creditor;
                @endphp
                <div class="flex items-center gap-4 bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-14 shrink-0 ring-2 ring-slate-100 dark:ring-slate-800"
                        style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($otherUser?->name ?? "?") }}&background=137fec&color=fff");'>
                    </div>
                    <div class="flex flex-1 flex-col justify-center min-w-0">
                        @if($isCreditor)
                        <p class="text-slate-900 dark:text-slate-100 text-base font-bold leading-normal truncate">
                            {{ $otherUser?->name ?? 'Unknown' }} <span class="mx-1 text-primary text-sm">→</span> You:
                            <span class="text-emerald-600 dark:text-emerald-400">+{{ number_format($p->amount, 2) }}€</span>
                        </p>
                        @else
                        <p class="text-slate-900 dark:text-slate-100 text-base font-bold leading-normal truncate">
                            You <span class="mx-1 text-primary text-sm">→</span> {{ $otherUser?->name ?? 'Unknown' }}:
                            <span class="text-rose-600 dark:text-rose-400">-{{ number_format($p->amount, 2) }}€</span>
                        </p>
                        @endif
                        <p class="text-slate-500 dark:text-slate-400 text-sm font-normal">
                            Settlement from {{ $p->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="shrink-0">
                        <form action="{{ route('payments.pay', $p) }}" method="POST">
                            @csrf
                            @if($isCreditor)
                            <button type="submit" class="flex min-w-[110px] items-center justify-center gap-2 rounded-lg h-10 px-4 bg-primary/10 text-primary hover:bg-primary hover:text-white text-sm font-bold transition-all">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                <span class="truncate">Mark Paid</span>
                            </button>
                            @else
                            <button type="submit" class="flex min-w-[110px] items-center justify-center gap-2 rounded-lg h-10 px-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white text-sm font-bold transition-all">
                                <span class="material-symbols-outlined text-[18px]">payments</span>
                                <span class="truncate">Pay Now</span>
                            </button>
                            @endif
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800">
                    <div class="inline-flex items-center justify-center size-16 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 mb-4">
                        <span class="material-symbols-outlined text-3xl">done_all</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2">You're all settled up!</h3>
                    <p class="text-slate-500 dark:text-slate-400">There are no pending settlements for you at the moment.</p>
                </div>
                @endforelse
            </div>

            {{-- Right: Summary Cards --}}
            <div class="flex flex-col gap-6">
                <h2 class="text-slate-900 dark:text-slate-100 text-[22px] font-bold leading-tight tracking-[-0.015em]">Settlement Summary</h2>

                @php
                $totalToReceive = $allPendingPayments->filter(fn($p) => $p->creditor_id == auth()->id())->sum('amount');
                $totalToPay = $allPendingPayments->filter(fn($p) => $p->debtor_id == auth()->id())->sum('amount');
                @endphp

                {{-- To Receive Card --}}
                <div class="relative overflow-hidden bg-emerald-600 dark:bg-emerald-700 rounded-2xl p-6 shadow-xl shadow-emerald-600/20 text-white">
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4 opacity-90">
                            <span class="material-symbols-outlined text-[24px]">call_received</span>
                            <span class="text-sm font-bold uppercase tracking-widest">Total to Receive</span>
                        </div>
                        <p class="text-4xl md:text-5xl font-black mb-2 leading-none">+{{ number_format($totalToReceive, 2) }}€</p>
                        <p class="text-emerald-100 text-sm font-medium">From {{ $credits->count() }} settlement(s)</p>
                    </div>
                    <div class="absolute -right-8 -bottom-8 opacity-10">
                        <span class="material-symbols-outlined text-[160px]">trending_up</span>
                    </div>
                </div>

                {{-- To Pay Card --}}
                <div class="relative overflow-hidden bg-rose-600 dark:bg-rose-700 rounded-2xl p-6 shadow-xl shadow-rose-600/20 text-white">
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4 opacity-90">
                            <span class="material-symbols-outlined text-[24px]">call_made</span>
                            <span class="text-sm font-bold uppercase tracking-widest">Total to Pay</span>
                        </div>
                        <p class="text-4xl md:text-5xl font-black mb-2 leading-none">-{{ number_format($totalToPay, 2) }}€</p>
                        <p class="text-rose-100 text-sm font-medium">To {{ $debts->count() }} roommate(s)</p>
                    </div>
                    <div class="absolute -right-8 -bottom-8 opacity-10">
                        <span class="material-symbols-outlined text-[160px]">trending_down</span>
                    </div>
                </div>

                {{-- Financial Health --}}
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
                    <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold mb-4">Net Balance</h3>
                    @php $net = $totalToReceive - $totalToPay; @endphp
                    <p class="text-3xl font-black {{ $net >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} mb-2">
                        {{ $net >= 0 ? '+' : '' }}{{ number_format($net, 2) }}€
                    </p>
                    <p class="text-xs text-slate-400">{{ $net >= 0 ? 'You are owed more than you owe.' : 'You owe more than you are owed.' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-coloc-layout>