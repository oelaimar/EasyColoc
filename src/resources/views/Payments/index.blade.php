<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto bg-white p-6 shadow rounded-lg">
            <h2 class="text-xl font-bold mb-4">Money Tracker</h2>

            <table class="w-full border-collapse">
                <thead>
                <tr class="border-b">
                    <th class="text-left p-2">From</th>
                    <th class="text-left p-2">To</th>
                    <th class="text-left p-2">Amount</th>
                    <th class="text-right p-2">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $p)
                    <tr class="border-b">
                        <td class="p-2">{{ $p->debtor->name }}</td>
                        <td class="p-2">{{ $p->creditor->name }}</td>
                        <td class="p-2 font-bold">{{ number_format($p->amount, 2) }}€</td>
                        <td class="p-2 text-right">
                            @if($p->status == 'pending')
                                <form action="{{ route('payments.pay', $p) }}" method="POST">
                                    @csrf
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm">
                                        Mark as Paid
                                    </button>
                                </form>
                            @else
                                <span class="text-green-600">Paid</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
