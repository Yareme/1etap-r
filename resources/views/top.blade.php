<div>
    <h1>Top</h1>


    @foreach($data as $item)
        <p>{{ $item->order }} - {{ $item->total_orders }} orders</p>
    @endforeach
</div>
