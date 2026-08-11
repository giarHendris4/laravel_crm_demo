<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Partner Company Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang, Partner {{ Auth::user()->name }}! 🤝</h3>
                <p class="text-gray-600">Portal mitra ini disiapkan untuk membantu Anda memantau status komisi, laporan rujukan (*referral*), dan kerja sama proyek.</p>
            </div>
        </div>
    </div>
</x-app-layout>