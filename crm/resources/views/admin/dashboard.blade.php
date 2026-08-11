<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Executive Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <p class="text-sm font-medium text-gray-500">Total User Terdaftar</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500">Tim Sales Rep</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_sales'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500">Partner Terkoneksi</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_partner'] }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang, Administrator!</h3>
                <p class="text-gray-600">Gunakan menu <strong>User Management</strong> untuk mengelola akun Sales Rep dan Partner Company.</p>
            </div>
        </div>
    </div>
</x-app-layout>