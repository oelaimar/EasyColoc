<x-app-layout>
    <div class="py-12 max-w-2xl mx-auto">
        <div class="bg-white p-6 shadow rounded-lg">
            <h2 class="text-xl font-bold mb-4">Manage House Categories</h2>

            <form method="POST" action="{{ route('categories.store') }}" class="flex gap-2 mb-6">
                @csrf
                <x-text-input name="name" placeholder="New Category (e.g. Internet)" class="flex-1" required />
                <x-primary-button>Add</x-primary-button>
            </form>

            <ul class="divide-y">
                @foreach($categories as $category)
                    <li class="py-2 flex justify-between items-center">
                        <span>{{ $category->name }}</span>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}">
                            @csrf @method('DELETE')
                            <button class="text-red-500 text-xs uppercase font-bold">Delete</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
