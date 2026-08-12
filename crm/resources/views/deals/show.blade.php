<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Deal') }}: {{ $deal->title }}
            </h2>
            <a href="{{ route('deals.edit', $deal) }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Edit
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Informasi Deal</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Judul Deal</dt>
                        <dd class="text-gray-900">{{ $deal->title }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Nilai Deal</dt>
                        <dd class="text-gray-900 font-semibold">Rp {{ number_format($deal->deal_value, 2, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Tahap (Stage)</dt>
                        <dd>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ strtoupper(str_replace('_', ' ', $deal->stage)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Target Closing</dt>
                        <dd class="text-gray-900">{{ optional($deal->expected_close_date)->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Sales (Pemilik)</dt>
                        <dd class="text-gray-900">{{ $deal->user->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Lead Asal</dt>
                        <dd class="text-gray-900">{{ $deal->lead->title ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Perusahaan Lead</dt>
                        <dd class="text-gray-900">{{ $deal->lead->company_name ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('deals.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
