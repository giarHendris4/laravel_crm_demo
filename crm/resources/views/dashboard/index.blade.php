<div>
    <h1>Dashboard Analytics Sales</h1>

    <div>
        <h3>Total Revenue:</h3>
        <p>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>

    <div>
        <h3>Conversion Rate:</h3>
        <p>{{ $conversionRate }}%</p>
    </div>
</div>