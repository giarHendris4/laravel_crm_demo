<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Executive Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total User Terdaftar</p>
                    <p class="mt-2 text-3xl font-bold text-gray-800 leading-none">{{ $stats['total_users'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Tim Sales Rep</p>
                    <p class="mt-2 text-3xl font-bold text-gray-800 leading-none">{{ $stats['total_sales'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-green-500">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Partner Terkoneksi</p>
                    <p class="mt-2 text-3xl font-bold text-gray-800 leading-none">{{ $stats['total_partner'] }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Tambah Data Baru</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
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
                    <a href="{{ route('admin.users.create') }}" class="block p-4 border border-dashed border-red-300 rounded-lg hover:bg-red-50 transition">
                        <div class="font-semibold text-red-700">+ Tambah User</div>
                        <div class="text-sm text-gray-500">Tambah Sales/Partner/Admin</div>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang, Administrator!</h3>
                <p class="text-gray-600">Gunakan menu <strong>User Management</strong> untuk mengelola akun Sales Rep dan Partner Company.</p>
            </div>
        </div>
    </div>
</x-app-layout>