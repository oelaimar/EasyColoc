<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏠 {{ $colocation->name }}
            </h2>
            <form method="POST" action="{{ route('colocations.leave') }}">
                @csrf
                <button type="submit" onclick="return confirm('Are you sure you want to leave?')" class="text-sm text-red-600 hover:underline">
                    Leave Colocation
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 font-bold uppercase">People owe you</p>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($totalToReceive, 2) }} €</p>
                    <a href="{{ route('payments.index') }}" class="text-xs text-blue-600 hover:underline">View details →</a>
                </div>

                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 font-bold uppercase">You owe others</p>
                    <p class="text-3xl font-black text-gray-900">{{ number_format($totalToPay, 2) }} €</p>
                    <a href="{{ route('payments.index') }}" class="text-xs text-blue-600 hover:underline">View details →</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 shadow rounded-lg md:col-span-1">
                    <h3 class="font-bold text-gray-700 border-b pb-2 mb-4">Roommates</h3>
                    <ul class="space-y-3">
                        @foreach($colocation->members as $member)
                            <li class="flex justify-between items-center text-sm">
                                <span>{{ $member->name }}</span>
                                @if($member->pivot->role === 'owner')
                                    <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-0.5 rounded">Owner</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-6 p-3 bg-gray-50 rounded border border-dashed border-gray-300">
                        <p class="text-[10px] uppercase text-gray-400 font-bold">Invite Token</p>
                        <code class="text-xs text-indigo-600 break-all">{{ $colocation->invite_token }}</code>
                    </div>
                </div>

                <div class="bg-white p-6 shadow rounded-lg md:col-span-2">
                    <div class="flex justify-between items-center border-b pb-2 mb-4">
                        <h3 class="font-bold text-gray-700">Recent House Activity</h3>
                        <a href="{{ route('expenses.index') }}" class="text-xs bg-indigo-600 text-white px-3 py-1 rounded">Add Expense</a>
                    </div>
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="text-gray-400">
                            <th class="pb-2">Title</th>
                            <th class="pb-2 text-right">Amount</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y">
                        @forelse($colocation->expenses as $expense)
                            <tr>
                                <td class="py-2">{{ $expense->title }}</td>
                                <td class="py-2 text-right font-semibold">{{ number_format($expense->amount, 2) }} €</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-4 text-center text-gray-400">No expenses yet.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
