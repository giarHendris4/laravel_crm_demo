<div>
    <h1>Daftar Deals / Pipeline</h1>

    <ul>
        @foreach ($deals as $deal)
            <li>{{ $deal->title }} - {{ $deal->stage }}</li>
        @endforeach
    </ul>
</div>