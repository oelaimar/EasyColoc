<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏠 {{ $colocation->name }}
            </h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full uppercase">
                Invite Token: {{ $colocation->invite_token }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Total House Expenses</p>
                    <p class="text-2xl font-black text-gray-800">{{ number_format($totalSpent, 2) }} €</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Your Reputation</p>
                    <p class="text-2xl font-black text-gray-800">{{ auth()->user()->reputation_score }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-500 uppercase font-bold">Active Members</p>
                    <p class="text-2xl font-black text-gray-800">{{ $colocation->members->count() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Roommates</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach($colocation->members as $member)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <span class="font-medium text-gray-900">{{ $member->name }}</span>
                                    <span class="ml-2 text-xs text-gray-400">({{ $member->pivot->role }})</span>
                                </div>
                                <span class="text-sm font-semibold {{ $member->reputation_score >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rep: {{ $member->reputation_score }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-white shadow sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold">Recent Expenses</h3>
                        <a href="#" class="text-xs text-indigo-600 hover:underline">View All</a>
                    </div>
                    <ul class="space-y-3">
                        @forelse($colocation->expenses as $expense)
                            <li class="flex justify-between text-sm">
                                <span>{{ $expense->title }}</span>
                                <span class="font-bold">{{ number_format($expense->amount, 2) }} €</span>
                            </li>
                        @empty
                            <p class="text-gray-500 text-sm">No expenses yet.</p>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
