<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales Representative Workspace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Halo, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-gray-600">Selamat bekerja! Di portal ini kamu dapat mengelola *leads*, membuat penawaran (*deals*), dan mencatat interaksi klien.</p>
            </div>
        </div>
    </div>
</x-app-layout>