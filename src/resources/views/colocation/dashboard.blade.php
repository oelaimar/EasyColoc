<x-coloc-layout>
    <div class="layout-content-container flex flex-col max-w-[1200px] flex-1 px-4 md:px-10 gap-8 mx-auto w-full">

        @php
        $currentUserRole = auth()->user()->memberships()->where('colocation_id', $colocation->id)->first()?->role;
        $isOwner = $currentUserRole === 'owner';
        $currentMembership = auth()->user()->memberships()->where('colocation_id', $colocation->id)->whereNull('left_at')->first();
        @endphp

        {{-- Header --}}
        <div class="flex flex-wrap items-center justify-between gap-6">
            <div class="flex flex-col gap-1">
                <h1 class="text-slate-900 dark:text-slate-100 text-4xl font-black leading-tight tracking-[-0.033em]">
                    {{ $colocation->name }}
                </h1>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-slate-500 text-sm">person</span>
                    <p class="text-slate-500 dark:text-slate-400 text-base font-medium leading-normal">
                        {{ ucfirst($currentUserRole ?? 'Member') }} Role
                    </p>
                </div>
            </div>

            @if($isOwner)
            {{-- Owner: Delete Colocation --}}
            <div x-data="{ openDeleteModal: false }">
                <button @click="openDeleteModal = true"
                    class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-6 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-red-100 transition-colors border border-red-200 dark:border-red-900/50">
                    <span class="material-symbols-outlined mr-2 text-lg">delete_forever</span>
                    <span class="truncate">Delete Colocation</span>
                </button>

                {{-- Delete Confirmation Modal --}}
                <div x-show="openDeleteModal" style="display:none;"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">
                    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openDeleteModal = false"></div>
                    <div class="relative bg-white dark:bg-slate-900 rounded-2xl p-8 max-w-md w-full shadow-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="size-12 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">delete_forever</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Delete Colocation?</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">This action cannot be undone.</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
                            All members will be removed and all expenses, categories, and settlements will be permanently deleted. Enter your password to confirm.
                        </p>
                        <form method="POST" action="{{ route('colocations.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <input type="password" name="password" required placeholder="Your password"
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none mb-4 text-sm">
                            @error('password', 'colocationDeletion')
                            <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                            @enderror
                            <div class="flex gap-3">
                                <button type="submit"
                                    class="flex-1 py-2.5 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-colors text-sm">
                                    Yes, Delete Everything
                                </button>
                                <button @click="openDeleteModal = false" type="button"
                                    class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-lg hover:bg-slate-200 transition-colors text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @else
            {{-- Member: Leave Colocation --}}
            @if($currentMembership)
            <form action="{{ route('memberships.leave', $currentMembership) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to leave this colocation?')"
                    class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-6 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-red-100 transition-colors border border-red-200 dark:border-red-900/50">
                    <span class="material-symbols-outlined mr-2 text-lg">logout</span>
                    <span class="truncate">Leave Colocation</span>
                </button>
            </form>
            @endif
            @endif
        </div>

        @if(session('success'))
        <div class="p-4 text-sm text-green-800 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            {{ session('success') }}
        </div>
        @endif



        {{-- Balances Overview --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 flex flex-col rounded-xl shadow-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <div>
                        <h3 class="text-slate-900 dark:text-slate-100 text-xl font-bold">Who Owes Who</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Current settlement status</p>
                    </div>
                    @php $netBalance = $totalToReceive - $totalToPay; @endphp
                    <div class="{{ $netBalance >= 0 ? 'bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400' }} px-4 py-2 rounded-lg font-bold text-lg">
                        Total: {{ $netBalance >= 0 ? '+' : '' }}{{ number_format($netBalance, 2) }}€
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4 p-6">
                    {{-- To Receive --}}
                    <div class="flex flex-col gap-3 rounded-lg p-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                <span class="material-symbols-outlined">call_received</span>
                            </div>
                            <p class="text-slate-900 dark:text-slate-100 text-base font-semibold">Total to Receive</p>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-emerald-600 dark:text-emerald-400 tracking-tight text-3xl font-black">
                                +{{ number_format($totalToReceive, 2) }}€
                            </p>
                            <span class="text-emerald-600 dark:text-emerald-400 text-sm font-bold flex items-center">
                                <span class="material-symbols-outlined text-sm">trending_up</span>
                            </span>
                        </div>
                    </div>
                    {{-- To Pay --}}
                    <div class="flex flex-col gap-3 rounded-lg p-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center">
                                <span class="material-symbols-outlined">call_made</span>
                            </div>
                            <p class="text-slate-900 dark:text-slate-100 text-base font-semibold">Total to Pay</p>
                        </div>
                        <div class="flex items-end justify-between">
                            <p class="text-red-600 dark:text-red-400 tracking-tight text-3xl font-black">
                                -{{ number_format($totalToPay, 2) }}€
                            </p>
                            <span class="text-red-600 dark:text-red-400 text-sm font-bold flex items-center">
                                <span class="material-symbols-outlined text-sm">trending_down</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-col gap-4">
                <a href="{{ route('expenses.index') }}"
                    class="flex w-full items-center justify-center gap-2 rounded-xl h-14 bg-primary text-white font-bold text-lg shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">
                    <span class="material-symbols-outlined">add_circle</span>
                    Add Expense
                </a>
                @if($isOwner)
                {{-- Invite Code: visible to owner only --}}
                <div x-data="{ copied: false }" class="bg-primary/10 dark:bg-primary/20 p-6 rounded-xl border border-primary/20">
                    <h4 class="text-primary font-bold mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">key</span> Invite Code
                    </h4>
                    <div class="flex items-center gap-2">
                        <p class="text-slate-900 dark:text-slate-100 text-sm font-black flex-1 break-all">{{ $colocation->invite_token }}</p>
                        <button
                            @click="navigator.clipboard.writeText('{{ $colocation->invite_token }}'); copied = true; setTimeout(() => copied = false, 2000)"
                            class="shrink-0 p-1.5 rounded-lg hover:bg-primary/20 transition-colors"
                            :title="copied ? 'Copied!' : 'Copy token'">
                            <span class="material-symbols-outlined text-primary text-lg" x-text="copied ? 'check' : 'content_copy'">content_copy</span>
                        </button>
                    </div>
                    <p x-show="!copied" class="text-slate-600 dark:text-slate-400 text-xs mt-2">Share this code with roommates to join.</p>
                    <p x-show="copied" class="text-emerald-600 dark:text-emerald-400 text-xs mt-2 font-semibold">Token copied to clipboard!</p>
                    <a href="{{ route('colocations.invite') }}" class="mt-3 flex items-center gap-1 text-xs text-primary font-semibold hover:underline">
                        <span class="material-symbols-outlined text-sm">person_add</span> Manage members
                    </a>
                </div>
                @endif
                <a href="{{ route('payments.index') }}"
                    class="flex w-full items-center justify-center gap-2 rounded-xl h-12 border-2 border-primary text-primary font-bold text-sm hover:bg-primary/5 transition-all">
                    <span class="material-symbols-outlined text-sm">payments</span>
                    View Settlements
                </a>
            </div>
        </div>

        {{-- Activity + Members --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Recent Activity --}}
            <div class="lg:col-span-2 flex flex-col gap-4">
                <div class="flex items-center justify-between px-2">
                    <h2 class="text-slate-900 dark:text-slate-100 text-2xl font-bold leading-tight tracking-[-0.015em]">Recent Expenses</h2>
                    <a href="{{ route('expenses.index') }}" class="text-primary text-sm font-bold hover:underline">View All</a>
                </div>
                <div class="flex flex-col rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($colocation->expenses as $expense)
                    @php
                    $icons = ['receipt_long','shopping_cart','bolt','cleaning_services','wifi','inventory_2'];
                    $colors = ['blue','orange','purple','green','slate','pink'];
                    $idx = $loop->index % 6;
                    @endphp
                    <div class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="size-10 rounded-lg bg-{{ $colors[$idx] }}-100 dark:bg-{{ $colors[$idx] }}-900/30 text-{{ $colors[$idx] }}-600 dark:text-{{ $colors[$idx] }}-400 flex items-center justify-center">
                                <span class="material-symbols-outlined">{{ $icons[$idx] }}</span>
                            </div>
                            <div>
                                <p class="text-slate-900 dark:text-slate-100 font-bold">{{ $expense->description }}</p>
                                <p class="text-slate-500 dark:text-slate-400 text-xs">Paid by {{ $expense->user->name }} • {{ $expense->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-slate-900 dark:text-slate-100 font-bold text-lg">{{ number_format($expense->amount, 2) }}€</p>
                    </div>
                    @empty
                    <div class="p-10 text-center text-slate-400 dark:text-slate-500">
                        <span class="material-symbols-outlined text-4xl mb-2 block opacity-40">receipt_long</span>
                        No recent expenses found.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Roommates --}}
            <div class="flex flex-col gap-4">
                <div class="px-2 flex items-center justify-between">
                    <h2 class="text-slate-900 dark:text-slate-100 text-2xl font-bold leading-tight tracking-[-0.015em]">Roommates</h2>
                    <a href="{{ route('colocations.invite') }}" class="text-primary text-sm font-bold hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">person_add</span> Invite
                    </a>
                </div>
                <div class="flex flex-col gap-3">
                    @foreach($colocation->members as $member)
                    @php $memberRep = $member->reputation_score ?? 0; @endphp
                    <div class="flex items-center gap-4 p-4 rounded-xl {{ $member->id === auth()->id() ? 'bg-primary/5 dark:bg-primary/10 border border-primary/20' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800' }}">
                        <div class="size-12 flex items-center justify-center rounded-full bg-cover bg-center ring-2 {{ $member->id === auth()->id() ? 'ring-primary/40' : 'ring-primary/10' }}"
                            style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=137fec&color=fff");'>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-slate-900 dark:text-slate-100 font-bold truncate">{{ $member->name }}</p>
                                @if($member->id === auth()->id())
                                <span class="bg-primary text-white text-[10px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">You</span>
                                @endif
                                {{-- Reputation Badge --}}
                                <span title="Reputation score" class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full font-bold
                                    {{ $memberRep >= 0 ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' }}">
                                    {{ $memberRep >= 0 ? '+' : '' }}{{ $memberRep }}
                                </span>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 text-xs">{{ ucfirst($member->pivot->role ?? 'Member') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Footer Stats Bar --}}
        <div class="flex flex-wrap gap-0 rounded-xl bg-slate-900 dark:bg-slate-800 text-white mt-4 mb-12 overflow-hidden">
            <div class="flex-1 min-w-[150px] p-6 border-r border-slate-700/50 last:border-0">
                <p class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-1">Total Spent</p>
                <p class="text-2xl font-black">{{ number_format($totalSpent, 2) }}€</p>
            </div>
            <div class="flex-1 min-w-[150px] p-6 border-r border-slate-700/50 last:border-0">
                <p class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-1">Roommates</p>
                <p class="text-2xl font-black">{{ $colocation->members->count() }}</p>
            </div>
            <div class="flex-1 min-w-[150px] p-6">
                <p class="text-slate-400 text-xs uppercase font-bold tracking-widest mb-1">Status</p>
                <p class="text-2xl font-black">{{ ucfirst($colocation->status) }}</p>
            </div>
        </div>
    </div>
</x-coloc-layout>