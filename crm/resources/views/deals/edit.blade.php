<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Deal') }}: {{ $deal->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('deals.update', $deal) }}">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-4">
                        <x-input-label for="title" :value="__('Judul Deal')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $deal->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Deal Value -->
                    <div class="mb-4">
                        <x-input-label for="deal_value" :value="__('Nilai Deal (Rp)')" />
                        <x-text-input id="deal_value" class="block mt-1 w-full" type="number" step="0.01" min="0" name="deal_value" :value="old('deal_value', $deal->deal_value)" required />
                        <x-input-error :messages="$errors->get('deal_value')" class="mt-2" />
                    </div>

                    <!-- Stage -->
                    <div class="mb-4">
                        <x-input-label for="stage" :value="__('Tahap (Stage)')" />
                        <select id="stage" name="stage" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            @foreach ([
                                'qualification' => 'Qualification',
                                'proposal' => 'Proposal',
                                'negotiation' => 'Negotiation',
                                'closed_won' => 'Closed Won',
                                'closed_lost' => 'Closed Lost',
                            ] as $value => $label)
                                <option value="{{ $value }}" {{ old('stage', $deal->stage) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('stage')" class="mt-2" />
                    </div>

                    <!-- Expected Close Date -->
                    <div class="mb-4">
                        <x-input-label for="expected_close_date" :value="__('Target Tanggal Closing')" />
                        <x-text-input id="expected_close_date" class="block mt-1 w-full" type="date" name="expected_close_date" :value="old('expected_close_date', optional($deal->expected_close_date)->format('Y-m-d'))" />
                        <x-input-error :messages="$errors->get('expected_close_date')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-4">
                        <a href="{{ route('deals.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
