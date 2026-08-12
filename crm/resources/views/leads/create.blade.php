<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Lead') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('leads.store') }}" method="POST">
                    @include('leads.partials.form', ['lead' => new \App\Models\Lead()])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
