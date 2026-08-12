<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Customer') }}: {{ $customer->company_name }}
            </h2>
            <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Edit
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Informasi Kontak</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Nama Perusahaan</dt>
                        <dd class="text-gray-900">{{ $customer->company_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Nama Kontak (PIC)</dt>
                        <dd class="text-gray-900">{{ $customer->contact_name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Email</dt>
                        <dd class="text-gray-900">{{ $customer->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Telepon</dt>
                        <dd class="text-gray-900">{{ $customer->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Alamat</dt>
                        <dd class="text-gray-900">{{ $customer->address ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Status</dt>
                        <dd>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $customer->status === 'inactive' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $customer->status === 'churned' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ strtoupper($customer->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Total Lifetime Value</dt>
                        <dd class="text-gray-900 font-semibold">Rp {{ number_format($customer->total_lifetime_value, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Sales (Pemilik)</dt>
                        <dd class="text-gray-900">{{ $customer->user->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Lead Asal</dt>
                        <dd class="text-gray-900">{{ $customer->lead->title ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Catatan</dt>
                        <dd class="text-gray-900">{{ $customer->notes ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('customers.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
