<div>
    <h1>Detail Lead: {{ $lead->title }}</h1>

    <h2>Riwayat Aktivitas</h2>
    <ul>
        @foreach ($lead->activities as $activity)
            <li>
                <strong>[{{ strtoupper($activity->type) }}]</strong> {{ $activity->subject }} 
                - {{ $activity->description }}
            </li>
        @endforeach
    </ul>
</div>