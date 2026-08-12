<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Analytics - Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Grid Card Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pipeline Value</div>
                    <div class="mt-2 text-2xl font-bold text-indigo-600">Rp {{ number_format($activePipelineValue, 0, ',', '.') }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Won Revenue</div>
                    <div class="mt-2 text-2xl font-bold text-green-600">Rp {{ number_format($wonRevenue, 0, ',', '.') }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Active Customers</div>
                    <div class="mt-2 text-2xl font-bold text-gray-800">{{ $totalCustomers }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $totalLeads }} total leads</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion Rate</div>
                    <div class="mt-2 text-2xl font-bold text-amber-600">{{ $conversionRate }}%</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Deal Terbaru -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">5 Deal Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stage</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($recentDeals as $deal)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $deal->title }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $deal->user->name ?? '-' }}</td>
                                        <td class="px-4 py-3 font-semibold text-gray-800">Rp {{ number_format($deal->deal_value, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ strtoupper(str_replace('_', ' ', $deal->stage)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada deal.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Breakdown per Stage -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Pipeline per Stage</h3>
                    @php
                        $stageLabels = [
                            'qualification' => 'Qualification',
                            'proposal'      => 'Proposal',
                            'negotiation'   => 'Negotiation',
                            'closed_won'    => 'Closed Won',
                            'closed_lost'   => 'Closed Lost',
                        ];
                    @endphp
                    <ul class="space-y-3">
                        @foreach ($stageLabels as $key => $label)
                            <li class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $label }}</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                    {{ $dealsByStage[$key] ?? 0 }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
