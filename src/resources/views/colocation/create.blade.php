<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Get Started') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-blue-500">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Create a New Colocation</h3>
                    <p class="text-sm text-gray-500 mb-6">Start a new group and invite your roommates to share expenses.</p>

                    <form method="POST" action="{{ route('colocations.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Colocation Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="e.g. My Awesome Apartment" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-6">
                            <x-primary-button class="w-full justify-center py-3">
                                {{ __('Create Colocation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-green-500">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Join Existing Colocation</h3>
                    <p class="text-sm text-gray-500 mb-6">Received an invite? Paste the secret token below to join your roommates.</p>

                    <form method="POST" action="{{ route('colocations.join') }}">
                        @csrf
                        <div>
                            <x-input-label for="token" :value="__('Invite Token')" />
                            <x-text-input id="token" class="block mt-1 w-full" type="text" name="token" required placeholder="Paste the 32-character token here" />
                            <x-input-error :messages="$errors->get('token')" class="mt-2" />
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Join Group') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
