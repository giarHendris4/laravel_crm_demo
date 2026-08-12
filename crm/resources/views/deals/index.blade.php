<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Deals / Pipeline') }}
            </h2>
            <a href="{{ route('deals.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Tambah Deal
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- KARTU (mobile 1 kolom, tablet 2 kolom) : 1 data per kartu, berurutan ke bawah --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 2xl:hidden gap-4">
                @forelse ($deals as $deal)
                    <div class="bg-white overflow-hidden shadow-sm border border-gray-200">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center justify-between gap-2">
                                <div class="font-semibold text-gray-900">{{ $deal->title }}</div>
                                <span class="shrink-0 px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $deal->stage === 'closed_won' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $deal->stage === 'closed_lost' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ !in_array($deal->stage, ['closed_won', 'closed_lost']) ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ strtoupper(str_replace('_', ' ', $deal->stage)) }}
                                </span>
                            </div>
                        </div>

                        <div class="px-4 py-3 space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Lead</span>
                                <span class="text-gray-800">{{ $deal->lead->title ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Sales</span>
                                <span class="text-gray-800">{{ $deal->user->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500">Nilai</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($deal->deal_value, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-3 text-sm">
                                <a href="{{ route('deals.show', $deal) }}" class="text-gray-600 hover:text-gray-900 font-medium">Detail</a>
                                <a href="{{ route('deals.edit', $deal) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                            </div>
                            <form action="{{ route('deals.destroy', $deal) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus deal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-md shadow-sm p-6 text-center text-gray-500">Belum ada deal.</div>
                @endforelse
            </div>

            {{-- TABEL (desktop & laptop) --}}
            <div class="hidden 2xl:block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Deal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lead</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stage</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($deals as $deal)
                                <tr>
                                    <td class="px-4 py-4 font-medium text-gray-900">{{ $deal->title }}</td>
                                    <td class="px-4 py-4 text-xs text-gray-600">{{ $deal->lead->title ?? '-' }}</td>
                                    <td class="px-4 py-4 text-xs text-gray-600">{{ $deal->user->name ?? '-' }}</td>
                                    <td class="px-4 py-4 font-semibold text-gray-800">Rp {{ number_format($deal->deal_value, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $deal->stage === 'closed_won' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $deal->stage === 'closed_lost' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ !in_array($deal->stage, ['closed_won', 'closed_lost']) ? 'bg-blue-100 text-blue-800' : '' }}">
                                            {{ strtoupper(str_replace('_', ' ', $deal->stage)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right space-x-3">
                                        <a href="{{ route('deals.show', $deal) }}" class="text-gray-600 hover:text-gray-900 font-medium">Detail</a>
                                        <a href="{{ route('deals.edit', $deal) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>

                                        <form action="{{ route('deals.destroy', $deal) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus deal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada deal.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $deals->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
