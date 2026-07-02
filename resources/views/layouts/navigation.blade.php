<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>

    <x-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.*')">
        {{ __('Lowongan') }}
    </x-nav-link>

    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
        {{ __('Profil') }}
    </x-nav-link>

    <x-nav-link :href="route('applications.history')" :active="request()->routeIs('applications.history')">
        {{ __('Riwayat Lamaran') }}
    </x-nav-link>
</div>