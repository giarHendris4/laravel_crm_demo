<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales Representative Workspace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Halo, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-gray-600">Ini ringkasan performa Anda. Kelola leads, deals, dan customer melalui menu di atas.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <p class="text-sm font-medium text-gray-500">Total Leads</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $myLeadsCount }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500">Customers</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $myCustomersCount }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-amber-500">
                    <p class="text-sm font-medium text-gray-500">Pipeline Value</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($myPipelineValue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500">Won Revenue</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($myWonRevenue, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Tambah Data Baru</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('leads.create') }}" class="block p-4 border border-dashed border-indigo-300 rounded-lg hover:bg-indigo-50 transition">
                        <div class="font-semibold text-indigo-700">+ Tambah Lead</div>
                        <div class="text-sm text-gray-500">Buat prospek baru</div>
                    </a>
                    <a href="{{ route('deals.create') }}" class="block p-4 border border-dashed border-blue-300 rounded-lg hover:bg-blue-50 transition">
                        <div class="font-semibold text-blue-700">+ Tambah Deal</div>
                        <div class="text-sm text-gray-500">Buat penawaran baru</div>
                    </a>
                    <a href="{{ route('customers.create') }}" class="block p-4 border border-dashed border-green-300 rounded-lg hover:bg-green-50 transition">
                        <div class="font-semibold text-green-700">+ Tambah Customer</div>
                        <div class="text-sm text-gray-500">Buat data customer baru</div>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Akses Cepat</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('leads.index') }}" class="block p-4 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition">
                        <div class="font-semibold text-indigo-700">Kelola Leads</div>
                        <div class="text-sm text-gray-500">Lihat & tambah prospek</div>
                    </a>
                    <a href="{{ route('deals.index') }}" class="block p-4 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                        <div class="font-semibold text-blue-700">Kelola Deals</div>
                        <div class="text-sm text-gray-500">Kelola pipeline penawaran</div>
                    </a>
                    <a href="{{ route('customers.index') }}" class="block p-4 border border-green-200 rounded-lg hover:bg-green-50 transition">
                        <div class="font-semibold text-green-700">Kelola Customers</div>
                        <div class="text-sm text-gray-500">Lihat daftar customer</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
