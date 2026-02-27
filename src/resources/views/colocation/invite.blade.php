<x-coloc-layout>
    <div class="layout-content-container flex flex-col max-w-[1024px] w-full px-4 gap-8 mx-auto">

        <!-- Page Header -->
        <div class="flex flex-col gap-2">
            <h1 class="text-slate-900 dark:text-slate-100 text-4xl font-black leading-tight tracking-[-0.033em]">
                Member Management
            </h1>
            <p class="text-slate-600 dark:text-slate-400 text-base font-normal">
                Invite new roommates and manage the current members of your colocation.
            </p>
        </div>

        @if(session('success'))
        <div class="p-4 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl">
            <ul>
                @foreach($errors->all() as $error)
                <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Invite Token Display -->
        <section class="rounded-xl bg-gradient-to-br from-primary/10 to-primary/5 dark:from-primary/20 dark:to-primary/10 p-6 border border-primary/20" x-data="{ copied: false }">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-primary flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-xl">key</span>
                        Your Colocation Invite Token
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                        Share this token with anyone you want to invite. They enter it on the join page.
                    </p>
                    <div class="flex items-center gap-3 bg-white dark:bg-slate-900 rounded-lg px-4 py-3 border border-primary/30 shadow-sm">
                        <code class="text-primary font-black text-lg tracking-wider flex-1 break-all select-all">
                            {{ auth()->user()->currentColocation->invite_token }}
                        </code>
                        <button
                            @click="navigator.clipboard.writeText('{{ auth()->user()->currentColocation->invite_token }}'); copied = true; setTimeout(() => copied = false, 2500)"
                            class="shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-lg font-semibold text-sm transition-all
                                   bg-primary/10 hover:bg-primary text-primary hover:text-white">
                            <span class="material-symbols-outlined text-base leading-none" x-text="copied ? 'check' : 'content_copy'">content_copy</span>
                            <span x-text="copied ? 'Copied!' : 'Copy'">Copy</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Send Email Invite — Owner only -->
        @php
        $colocation = auth()->user()->currentColocation;
        $currentRole = $colocation
        ? $colocation->memberships()->where('user_id', auth()->id())->whereNull('left_at')->value('role')
        : null;
        $isOwner = $currentRole === 'owner';
        @endphp

        @if($isOwner)
        <section class="rounded-xl bg-white dark:bg-slate-900 p-6 shadow-sm border border-slate-200 dark:border-slate-800">
            <form action="{{ route('colocations.sendInvite') }}" method="POST">
                @csrf
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="flex flex-col flex-1 gap-6">
                        <div class="flex flex-col gap-2">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Send Email Invitation</h2>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                We'll email the invite token directly to a potential new roommate so they can join your colocation.
                            </p>
                        </div>
                        <div class="flex flex-col gap-4 max-w-md">
                            <label class="flex flex-col gap-1.5">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Recipient Email Address</span>
                                <div class="flex gap-2">
                                    <input name="email"
                                        class="form-input flex-1 rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:border-primary focus:ring-primary h-12 px-4 text-base"
                                        placeholder="roommate@example.com" type="email" required />
                                    <button type="submit"
                                        class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center gap-2 whitespace-nowrap">
                                        <span class="material-symbols-outlined text-[20px]">send</span>
                                        Send Invite
                                    </button>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="hidden md:flex w-32 h-32 items-center justify-center rounded-2xl bg-primary/10 dark:bg-primary/20">
                        <span class="material-symbols-outlined text-primary text-6xl opacity-60">mail</span>
                    </div>
                </div>
            </form>
        </section>
        @else
        <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-500 dark:text-slate-400">
            <span class="material-symbols-outlined text-lg shrink-0">lock</span>
            <p class="text-sm">Only the colocation owner can send invitations.</p>
        </div>
        @endif

        <!-- Current Members Table -->
        <section class="flex flex-col gap-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">group</span>
                <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">Current Members</h3>
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                            <th class="px-6 py-4 text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Member</th>
                            <th class="px-6 py-4 text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Reputation</th>
                            <th class="px-6 py-4 text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @php
                        $colocation = auth()->user()->currentColocation;
                        $members = $colocation ? $colocation->members()->wherePivotNull('left_at')->get() : collect([]);
                        @endphp
                        @foreach($members as $member)
                        @php $memberRep = $member->reputation_score ?? 0; @endphp
                        <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="size-9 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center overflow-hidden shrink-0">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=137fec&color=fff" class="w-full h-full object-cover" alt="{{ $member->name }}">
                                </div>
                                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ $member->name }}
                                    @if($member->id === auth()->id())
                                    <span class="ml-1 bg-primary text-white text-[10px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">You</span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $member->pivot->role === 'owner' ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                                    {{ ucfirst($member->pivot->role ?? 'Member') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold
                                    {{ $memberRep >= 0 ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' }}">
                                    <span class="material-symbols-outlined text-xs leading-none">{{ $memberRep >= 0 ? 'thumb_up' : 'thumb_down' }}</span>
                                    {{ $memberRep >= 0 ? '+' : '' }}{{ $memberRep }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($member->id === auth()->id() || $member->pivot->role === 'owner')
                                <span class="text-xs text-slate-400 italic">Protected</span>
                                @else
                                @php
                                $membership = $colocation->memberships()->where('user_id', $member->id)->whereNull('left_at')->first();
                                @endphp
                                @if($membership)
                                <form method="POST" action="{{ route('memberships.leave', $membership) }}"
                                    onsubmit="return confirm('Remove {{ $member->name }} from this colocation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 px-3 py-1.5 rounded-lg transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">person_remove</span>
                                        Remove
                                    </button>
                                </form>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Info notice -->
        <section class="flex items-center gap-2 px-4 py-3 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/40 rounded-xl text-amber-700 dark:text-amber-400 mb-4">
            <span class="material-symbols-outlined text-lg shrink-0">info</span>
            <p class="text-sm font-medium">
                Invitations sent by email are not tracked in the system. Members join using the invite token directly.
            </p>
        </section>
    </div>
</x-coloc-layout>