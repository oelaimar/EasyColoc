<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Expenses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Expense</h3>
                <form method="POST" action="{{ route('expenses.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="amount" value="Amount (€)" />
                        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="category_id" value="Category" />
                        <select name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="date" value="Date" />
                        <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" value="{{ date('Y-m-d') }}" required />
                    </div>
                    <div class="md:col-span-4 flex justify-end">
                        <x-primary-button>{{ __('Add Expense') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Expense History</h3>

                    <form method="GET" action="{{ route('expenses.index') }}" class="flex items-center gap-2">
                        <select name="month" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="all">All Months</option>
                            @for ($i = 0; $i < 6; $i++)
                                @php $m = now()->subMonths($i); @endphp
                                <option value="{{ $m->format('Y-m') }}" {{ request('month') == $m->format('Y-m') ? 'selected' : '' }}>
                                    {{ $m->format('F Y') }}
                                </option>
                            @endfor
                        </select>
                        <x-secondary-button type="submit">Filter</x-secondary-button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Payer</th>
                            <th class="px-4 py-3 text-right">Amount</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y">
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="px-4 py-3">{{ $expense->date }}</td>
                                <td class="px-4 py-3">{{ $expense->title }} <span class="text-xs text-gray-400">({{ $expense->category->name }})</span></td>
                                <td class="px-4 py-3">{{ $expense->payer->name }}</td>
                                <td class="px-4 py-3 text-right font-bold">{{ number_format($expense->amount, 2) }} €</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">No expenses found for this period.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
