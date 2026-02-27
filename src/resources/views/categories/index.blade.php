<x-coloc-layout>
    <div class="layout-content-container flex flex-col max-w-[800px] flex-1 px-4 md:px-10 gap-8 mx-auto w-full">

        {{-- Page Header --}}
        <div class="flex flex-col gap-1">
            <h1 class="text-slate-900 dark:text-slate-100 text-4xl font-black leading-tight tracking-[-0.033em]">
                Expense Categories
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-base font-normal">
                Manage the categories used to classify shared expenses in your colocation.
            </p>
        </div>

        @if(session('success'))
        <div class="p-4 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-red-600">error</span>
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li class="text-sm">{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

            {{-- Add Category Form --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-6 py-5 bg-primary/5 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">add_circle</span>
                        Add New Category
                    </h2>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('categories.store') }}" class="flex flex-col gap-4">
                        @csrf
                        <div class="flex flex-col gap-1.5">
                            <label class="text-slate-700 dark:text-slate-300 text-sm font-semibold">Category Name</label>
                            <input name="name" required
                                class="w-full rounded-lg border-slate-200 dark:border-slate-800 dark:bg-slate-950 dark:text-white focus:ring-primary focus:border-primary text-sm p-3"
                                placeholder="e.g. Internet, Rent, Groceries" type="text" />
                        </div>
                        <button type="submit"
                            class="w-full py-3 bg-primary text-white font-bold rounded-lg shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Save Category
                        </button>
                    </form>
                </div>
            </div>

            {{-- Category List --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-400">label</span>
                        Current Categories
                    </h2>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-full">
                        {{ $categories->count() }} total
                    </span>
                </div>
                <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($categories as $category)
                    <li class="flex items-center justify-between px-6 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-sm">sell</span>
                            <span class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $category->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}"
                            onsubmit="return confirm('Delete category \'{{ $category->name }}\'?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-xs font-bold text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/10 px-2.5 py-1.5 rounded-lg transition-all flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                Delete
                            </button>
                        </form>
                    </li>
                    @empty
                    <li class="p-10 text-center text-slate-400 dark:text-slate-500">
                        <span class="material-symbols-outlined text-4xl mb-2 block opacity-40">label_off</span>
                        No categories yet. Add one to get started.
                    </li>
                    @endforelse
                </ul>
            </div>

        </div>

    </div>
</x-coloc-layout>