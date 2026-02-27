<x-coloc-layout>
    <div class="px-6 py-8 lg:px-40 max-w-7xl mx-auto w-full">

        {{-- Page Header --}}
        <div class="flex flex-wrap justify-between items-end gap-4 mb-8">
            <div class="flex flex-col gap-1">
                <h1 class="text-slate-900 dark:text-white text-3xl font-black tracking-tight">Shared Expenses</h1>
                <p class="text-slate-500 dark:text-slate-400 text-base">Track and split bills with your roommates seamlessly.</p>
            </div>
            <div class="flex gap-3">
                <form method="GET" action="{{ route('expenses.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <select name="month" onchange="this.form.submit()"
                            class="appearance-none bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg px-4 py-2 pr-10 text-sm font-medium text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            <option value="all" {{ request('month') == 'all' || !request('month') ? 'selected' : '' }}>All Months</option>
                            @for ($i = 0; $i < 6; $i++)
                                @php $m=now()->subMonths($i); @endphp
                                <option value="{{ $m->format('Y-m') }}" {{ request('month') == $m->format('Y-m') ? 'selected' : '' }}>
                                    {{ $m->format('F Y') }}
                                </option>
                                @endfor
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">expand_more</span>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span> {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li class="text-sm">{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Expenses Table --}}
            <div class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Date</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Title</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Category</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Amount</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Payer</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($expenses as $expense)
                            @php
                            $categoryColors = ['Utilities'=>'blue','Groceries'=>'green','Entertainment'=>'purple','Rent'=>'orange','Household'=>'slate'];
                            $color = $categoryColors[$expense->category->name ?? ''] ?? 'slate';
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-white">{{ $expense->title }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 rounded-md bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 text-{{ $color }}-700 dark:text-{{ $color }}-300 text-xs font-medium">
                                        {{ $expense->category->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white text-right">{{ number_format($expense->amount, 2) }}€</td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $expense->user->name ?? 'System' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 block mb-2">receipt_long</span>
                                    <p class="text-slate-500 dark:text-slate-400">No expenses found for this period.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="lg:col-span-4 flex flex-col gap-6">

                {{-- Add Expense Form --}}
                <form method="POST" action="{{ route('expenses.store') }}">
                    @csrf
                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
                        <div class="px-6 py-5 bg-primary/5 border-b border-slate-200 dark:border-slate-800">
                            <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">add_circle</span>
                                New Expense
                            </h2>
                        </div>
                        <div class="p-6 space-y-5">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Title</label>
                                <input name="title" required
                                    class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white focus:ring-primary focus:border-primary text-sm p-3"
                                    placeholder="e.g. Weekly Groceries" type="text" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Amount</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">€</span>
                                        <input name="amount" required step="0.01" min="0.01"
                                            class="w-full pl-7 rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white focus:ring-primary focus:border-primary text-sm p-3"
                                            placeholder="0.00" type="number" />
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Date</label>
                                    <input name="date" required value="{{ date('Y-m-d') }}"
                                        class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white focus:ring-primary focus:border-primary text-sm p-3"
                                        type="date" />
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Category</label>
                                <div class="relative">
                                    <select name="category_id" required
                                        class="w-full appearance-none rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white focus:ring-primary focus:border-primary text-sm p-3 pr-10">
                                        <option disabled selected value="">Select category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">expand_more</span>
                                </div>
                                @if($categories->isEmpty())
                                <p class="text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">warning</span>
                                    No categories yet.
                                    <a href="{{ route('categories.index') }}" class="underline font-semibold hover:text-amber-800 dark:hover:text-amber-300">Create one first →</a>
                                </p>
                                @else
                                <a href="{{ route('categories.index') }}" class="text-xs text-slate-400 hover:text-primary transition-colors flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">edit</span>
                                    Manage categories
                                </a>
                                @endif
                            </div>
                            <button type="submit"
                                class="w-full py-3 bg-primary text-white font-bold rounded-lg shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2 mt-4">
                                <span class="material-symbols-outlined">save</span>
                                Save Expense
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Summary Card --}}
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6">
                    <h3 class="text-slate-900 dark:text-white font-bold mb-4">Period Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 dark:text-slate-400 text-sm">Total Displayed</span>
                            <span class="text-slate-900 dark:text-white font-bold">{{ number_format($expenses->sum('amount'), 2) }}€</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                            <div class="bg-primary h-full rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-xs text-slate-400">{{ $expenses->count() }} expense(s) in this period.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-coloc-layout>