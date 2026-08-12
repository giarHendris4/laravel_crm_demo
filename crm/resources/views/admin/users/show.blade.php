<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail User') }}: {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Edit
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Informasi Akun</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Nama Lengkap</dt>
                        <dd class="text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Email</dt>
                        <dd class="text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Role</dt>
                        <dd>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : ($user->role === 'sales' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500">Tanggal Dibuat</dt>
                        <dd class="text-gray-900">{{ $user->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Statistik</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $user->leads_count }}</div>
                        <div class="text-xs font-medium text-gray-500 uppercase mt-1">Leads</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $user->deals_count }}</div>
                        <div class="text-xs font-medium text-gray-500 uppercase mt-1">Deals</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $user->customers_count }}</div>
                        <div class="text-xs font-medium text-gray-500 uppercase mt-1">Customers</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
