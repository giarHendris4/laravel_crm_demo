<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Lead') }}: {{ $lead->title }}
            </h2>
            <a href="{{ route('leads.edit', $lead) }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Edit
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Informasi Lead</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Judul Lead</dt>
                        <dd class="text-gray-900">{{ $lead->title }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Perusahaan</dt>
                        <dd class="text-gray-900">{{ $lead->company_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Kontak (PIC)</dt>
                        <dd class="text-gray-900">{{ $lead->contact_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Kategori</dt>
                        <dd class="text-gray-900">{{ $lead->category->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Email</dt>
                        <dd class="text-gray-900">{{ $lead->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Telepon</dt>
                        <dd class="text-gray-900">{{ $lead->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Nilai Potensi</dt>
                        <dd class="text-gray-900 font-semibold">Rp {{ number_format($lead->opportunity_value, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Status</dt>
                        <dd>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $lead->status === 'won' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $lead->status === 'lost' ? 'bg-red-100 text-red-800' : '' }}
                                {{ !in_array($lead->status, ['won', 'lost']) ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ strtoupper($lead->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Sales Penangan</dt>
                        <dd class="text-gray-900">{{ $lead->user->name ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Riwayat Aktivitas</h3>
                @forelse ($lead->activities as $activity)
                    <div class="border-l-4 border-indigo-200 pl-4 py-2 mb-3">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-sm font-semibold text-gray-800">[{{ strtoupper($activity->type) }}] {{ $activity->subject }}</span>
                            <span class="text-xs text-gray-500">{{ $activity->performed_at->format('d M Y H:i') }} - {{ $activity->user->name ?? 'Unknown' }}</span>
                        </div>
                        @if ($activity->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $activity->description }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada aktivitas.</p>
                @endforelse
            </div>

            <div class="flex justify-end">
                <a href="{{ route('leads.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
