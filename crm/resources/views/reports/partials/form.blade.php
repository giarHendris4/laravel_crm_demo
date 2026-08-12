@props(['action', 'title', 'description'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $title }}</h3>
    <p class="text-sm text-gray-500 mb-4">{{ $description }}</p>

    <form method="GET" action="{{ $action }}" x-data="{ period: 'daily' }">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Periode -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Periode</label>
                <select name="period" x-model="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="daily">Harian (Hari Ini)</option>
                    <option value="weekly">Mingguan (7 Hari Terakhir)</option>
                    <option value="custom">Custom (Tanggal)</option>
                </select>
            </div>

            <!-- Format -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Format</label>
                <select name="format" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="xlsx">Excel (.xlsx)</option>
                    <option value="csv">CSV (.csv)</option>
                </select>
            </div>

            <!-- Custom Tanggal (tampil jika periode custom) -->
            <div x-show="period === 'custom'" x-cloak class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" name="start" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                </div>
            </div>

            <!-- Submit -->
            <div class="sm:col-span-2">
                <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700 transition">
                    Export
                </button>
            </div>
        </div>
    </form>
</div>
