<div>
<h1>Grupa</h1>

    @php
        $groupedData = collect($data)->groupBy('client_count');
    @endphp

    @foreach($groupedData as $clientCount => $countries)
        <p>{{ implode(', ', $countries->pluck('country')->toArray()) }} - {{ $clientCount }} klijentów</p>
    @endforeach




</div>
