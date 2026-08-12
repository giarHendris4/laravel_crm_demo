<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lead Ditugaskan kepada Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- KARTU (khusus mobile) : satu per satu berurutan ke bawah --}}
            <div class="space-y-4 sm:hidden">
                @forelse ($leads as $lead)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center justify-between gap-2">
                                <div class="font-semibold text-gray-900">{{ $lead->title }}</div>
                                <span class="shrink-0 px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $lead->status === 'won' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $lead->status === 'lost' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ !in_array($lead->status, ['won', 'lost']) ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ strtoupper($lead->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="px-4 py-3 space-y-2 text-sm">
                            <div>
                                <div class="font-medium text-gray-500">{{ $lead->company_name }}</div>
                                <div class="text-gray-600">{{ $lead->contact_name }} ({{ $lead->phone ?? '-' }})</div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Nilai Potensi</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($lead->opportunity_value, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Sales</span>
                                <span class="text-gray-800">{{ $lead->user->name ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="px-4 py-3 border-t border-gray-100">
                            <form action="{{ route('partner.leads.update-status', $lead) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="flex-1 rounded-md border-gray-300 shadow-sm text-sm">
                                    @foreach (['new' => 'New', 'contacted' => 'Contacted', 'proposal' => 'Proposal', 'negotiation' => 'Negotiation', 'won' => 'Won', 'lost' => 'Lost'] as $value => $label)
                                        <option value="{{ $value }}" {{ $lead->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="shrink-0 px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs font-semibold hover:bg-indigo-700">
                                    Simpan
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-md shadow-sm p-6 text-center text-gray-500">Belum ada lead yang ditugaskan.</div>
                @endforelse
            </div>

            {{-- TABEL (layar sedang & besar) --}}
            <div class="hidden sm:block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Lead</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan / PIC</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Potensi</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Update Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($leads as $lead)
                                <tr>
                                    <td class="px-4 py-4 font-medium text-gray-900">{{ $lead->title }}</td>
                                    <td class="px-4 py-4">
                                        <div class="font-semibold">{{ $lead->company_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $lead->contact_name }} ({{ $lead->phone ?? '-' }})</div>
                                    </td>
                                    <td class="px-4 py-4 text-xs text-gray-600">{{ $lead->user->name ?? '-' }}</td>
                                    <td class="px-4 py-4 font-semibold text-gray-800">Rp {{ number_format($lead->opportunity_value, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $lead->status === 'won' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $lead->status === 'lost' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ !in_array($lead->status, ['won', 'lost']) ? 'bg-blue-100 text-blue-800' : '' }}">
                                            {{ strtoupper($lead->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <form action="{{ route('partner.leads.update-status', $lead) }}" method="POST" class="flex flex-col sm:flex-row gap-2 sm:justify-end">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="rounded-md border-gray-300 shadow-sm text-sm">
                                                @foreach (['new' => 'New', 'contacted' => 'Contacted', 'proposal' => 'Proposal', 'negotiation' => 'Negotiation', 'won' => 'Won', 'lost' => 'Lost'] as $value => $label)
                                                    <option value="{{ $value }}" {{ $lead->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-xs font-semibold hover:bg-indigo-700">
                                                Simpan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada lead yang ditugaskan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $leads->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
