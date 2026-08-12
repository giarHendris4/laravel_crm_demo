<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-[auto_1fr_auto] items-center h-16">
            <!-- Brand (kiri) -->
            <div class="flex items-center">
                <a href="{{ auth()->user()->role === 'partner' ? route('partner.leads.index') : route('dashboard') }}" class="text-xl font-bold text-gray-800 hover:text-gray-600">
                    LARAVEL CRM
                </a>
            </div>

            <!-- Navigation Links (tengah, desktop besar) -->
            <div class="hidden 2xl:flex items-center justify-center min-w-0 px-2 gap-3 lg:gap-8">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>

                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'sales')
                    <x-nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">
                        {{ __('Leads') }}
                    </x-nav-link>

                    <x-nav-link :href="route('deals.index')" :active="request()->routeIs('deals.*')">
                        {{ __('Deals') }}
                    </x-nav-link>

                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                        {{ __('Customers') }}
                    </x-nav-link>
                @endif

                @if (Auth::user()->role === 'partner')
                    <x-nav-link :href="route('partner.leads.index')" :active="request()->routeIs('partner.leads.*')">
                        {{ __('Leads Ditugaskan') }}
                    </x-nav-link>
                @endif

                @if (Auth::user()->role === 'admin')
                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('User Management') }}
                    </x-nav-link>
                @endif

                <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    {{ __('Laporan') }}
                </x-nav-link>
            </div>

            <!-- Kanan: dropdown user + hamburger -->
            <div class="flex items-center justify-end">
                <!-- Settings Dropdown -->
                <div class="hidden 2xl:flex 2xl:items-center 2xl:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger (Mobile, Tablet & iPad Pro Button) -->
                <div class="-me-2 flex items-center 2xl:hidden">
                    <button @click="open = ! open" aria-label="Buka menu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Slide Menu (Mobile) -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-full opacity-0"
         @click.away="open = false"
         class="2xl:hidden fixed inset-x-0 top-16 z-40 bg-white shadow-lg border-b border-gray-100"
         style="will-change: transform;"
         aria-label="Menu navigasi">
        <!-- Header Menu + Tombol Close -->
        <div class="flex items-center justify-between px-4 py-3">
            <span class="font-medium text-gray-800">{{ Auth::user()->name }}</span>
            <button @click="open = false" aria-label="Tutup menu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'sales')
                <x-responsive-nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">
                    {{ __('Leads') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('deals.index')" :active="request()->routeIs('deals.*')">
                    {{ __('Deals') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">
                    {{ __('Customers') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->role === 'partner')
                <x-responsive-nav-link :href="route('partner.leads.index')" :active="request()->routeIs('partner.leads.*')">
                    {{ __('Leads Ditugaskan') }}
                </x-responsive-nav-link>
            @endif

            @if (Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('User Management') }}
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                {{ __('Laporan') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
