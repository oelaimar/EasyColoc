<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="p-6 bg-white shadow rounded-lg">
                <h3 class="text-lg font-bold mb-4">Start a New Colocation</h3>
                <form method="POST" action="{{ route('colocations.store') }}">
                    @csrf
                    <x-input-label for="name" value="House Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="e.g. The Penthouse" required />
                    <x-primary-button class="mt-4">Create House</x-primary-button>
                </form>
            </div>

            <div class="p-6 bg-white shadow rounded-lg">
                <h3 class="text-lg font-bold mb-4">Join with Invite Token</h3>
                <form method="POST" action="{{ route('colocations.join') }}">
                    @csrf
                    <x-input-label for="token" value="Token" />
                    <x-text-input id="token" name="token" type="text" class="mt-1 block w-full" placeholder="Paste 32-character token here" required />
                    <x-primary-button class="mt-4 bg-gray-800">Join House</x-primary-button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
