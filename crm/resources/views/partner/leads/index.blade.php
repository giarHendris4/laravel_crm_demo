<div>
    <h1>Portal Partner - Daftar Lead</h1>

    <ul>
        @forelse ($leads as $lead)
            <li>
                <strong>{{ $lead->title }}</strong> - {{ $lead->company_name }}
            </li>
        @empty
            <li>Belum ada lead yang ditugaskan.</li>
        @endforelse
    </ul>
</div>