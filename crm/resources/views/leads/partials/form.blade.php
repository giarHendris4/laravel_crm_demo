@csrf

<div class="space-y-6">
    <!-- Judul Lead -->
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Judul Lead</label>
        <input type="text" name="title" id="title" 
               value="{{ old('title', $lead->title ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
               placeholder="Contoh: Pengadaan LPG Restaurant XYZ" required>
        @error('title')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Perusahaan -->
    <div>
        <label for="company_name" class="block text-sm font-medium text-gray-700">Nama Perusahaan / Klien</label>
        <input type="text" name="company_name" id="company_name" 
               value="{{ old('company_name', $lead->company_name ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
               placeholder="Contoh: PT Kuliner Nusantara" required>
        @error('company_name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Nama Kontak (Contact Person) -->
    <div>
        <label for="contact_name" class="block text-sm font-medium text-gray-700">Nama Kontak (PIC)</label>
        <input type="text" name="contact_name" id="contact_name" 
               value="{{ old('contact_name', $lead->contact_name ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
               placeholder="Contoh: Bpk. Ahmad Subagja" required>
        @error('contact_name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email & Telepon -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Kontak</label>
            <input type="email" name="email" id="email" 
                   value="{{ old('email', $lead->email ?? '') }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                   placeholder="ahmad@example.com">
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">No. Telepon / WhatsApp</label>
            <input type="text" name="phone" id="phone" 
                   value="{{ old('phone', $lead->phone ?? '') }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                   placeholder="081234567890">
            @error('phone')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Nilai Potensi (Opportunity Value) -->
    <div>
        <label for="opportunity_value" class="block text-sm font-medium text-gray-700">Nilai Potensi (Rp)</label>
        <input type="number" name="opportunity_value" id="opportunity_value" step="0.01" 
               value="{{ old('opportunity_value', $lead->opportunity_value ?? '') }}" 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
               placeholder="10000000" required>
        @error('opportunity_value')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Status (Sesuai Controller: new, contacted, proposal, negotiation, won, lost) -->
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status Lead</label>
        <select name="status" id="status" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
            @php
                $currentStatus = old('status', $lead->status ?? 'new');
                $statuses = [
                    'new' => 'New (Baru)',
                    'contacted' => 'Contacted (Sudah Dihubungi)',
                    'proposal' => 'Proposal Sent',
                    'negotiation' => 'Negotiation (Negosiasi)',
                    'won' => 'Won (Berhasil)',
                    'lost' => 'Lost (Gagal)',
                ];
            @endphp
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 flex items-center justify-end space-x-3">
    <a href="{{ route('leads.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:bg-gray-50">
        Batal
    </a>
    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
        Simpan
    </button>
</div>