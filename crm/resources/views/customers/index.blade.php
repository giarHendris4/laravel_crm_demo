<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customer Management') }}
            </h2>
            <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Tambah Customer
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan / PIC</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lead Asal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lifetime Value</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse ($customers as $customer)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-gray-900">{{ $customer->company_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $customer->contact_name }} ({{ $customer->phone ?? '-' }})</div>
                                    </td>
                                    <td class="px-4 py-4 text-xs text-gray-600">
                                        {{ $customer->user->name ?? 'Unassigned' }}
                                    </td>
                                    <td class="px-4 py-4 text-xs text-gray-600">
                                        {{ $customer->lead->title ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 font-semibold text-gray-800">
                                        Rp {{ number_format($customer->total_lifetime_value, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $customer->status === 'inactive' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $customer->status === 'churned' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ strtoupper($customer->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-right space-x-2">
                                        <a href="{{ route('customers.edit', $customer) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>

                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus customer ini?')">
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
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data customer.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
