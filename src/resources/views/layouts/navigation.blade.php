<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>

    @if(auth()->user()->current_colocation_id)
        <x-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.index')">
            {{ __('Expenses') }}
        </x-nav-link>

        <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.index')">
            {{ __('Payments') }}
        </x-nav-link>
    @endif
</div>
