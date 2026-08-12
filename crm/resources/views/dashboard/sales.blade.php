<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Performa Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Grid Card Statistik Performa Sales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Leads Saya</div>
                    <div class="mt-2 text-2xl font-bold text-indigo-600">{{ $myLeadsCount }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Customers Aktif Saya</div>
                    <div class="mt-2 text-2xl font-bold text-gray-800">{{ $myCustomersCount }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pipeline Value Saya</div>
                    <div class="mt-2 text-2xl font-bold text-amber-600">Rp {{ number_format($myPipelineValue, 0, ',', '.') }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Won Revenue Saya</div>
                    <div class="mt-2 text-2xl font-bold text-green-600">Rp {{ number_format($myWonRevenue, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Win Rate -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Win Rate</div>
                    <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $winRate }}%</div>
                    <div class="text-xs text-gray-500 mt-1">Persentase deal yang berhasil dimenangkan.</div>
                </div>

                <!-- 5 Lead Terbaru -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">5 Lead Terbaru Saya</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Potensi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($myRecentLeads as $lead)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $lead->title }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $lead->company_name }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $lead->category->name ?? '-' }}</td>
                                        <td class="px-4 py-3 font-semibold text-gray-800">Rp {{ number_format($lead->opportunity_value, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ strtoupper($lead->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada lead.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
