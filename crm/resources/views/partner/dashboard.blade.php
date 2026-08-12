<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Partner Company Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang, Partner {{ Auth::user()->name }}! 🤝</h3>
                <p class="text-gray-600">Kelola lead yang ditugaskan kepada Anda melalui portal mitra ini.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <p class="text-sm font-medium text-gray-500">Total Lead Ditugaskan</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAssignedLeads }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalAssignedLeads > 0 ? 'Aktif' : 'Belum Ada' }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500">Aksi</p>
                    <a href="{{ route('partner.leads.index') }}" class="inline-block mt-1 text-blue-600 font-semibold hover:text-blue-800">
                        Buka Daftar Lead →
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
