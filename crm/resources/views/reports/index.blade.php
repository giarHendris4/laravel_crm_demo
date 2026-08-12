<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan & Export') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-4 bg-indigo-50 border-l-4 border-indigo-500 rounded-md text-sm text-indigo-800">
                Export diproses di latar belakang (queue). File Excel/CSV akan tersedia setelah job selesai.
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @include('reports.partials.form', [
                    'action' => route('export.leads'),
                    'title' => 'Export Data Leads',
                    'description' => 'Unduh seluruh data leads sesuai periode yang dipilih. Admin melihat semua, Sales hanya miliknya, Partner hanya lead yang ditugaskan.',
                ])

                @include('reports.partials.form', [
                    'action' => route('export.sales'),
                    'title' => 'Export Laporan Penjualan',
                    'description' => 'Unduh laporan deal yang berhasil dimenangkan (closed won) sesuai periode.',
                ])
            </div>
        </div>
    </div>
</x-app-layout>
