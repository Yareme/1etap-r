<div>
    <h1>Status</h1>


    @php
        $groupedData = collect($data)->groupBy('status');
    @endphp

    <table>
        <thead>
        <tr>
            <th>Status</th>
            <th>Status Counter</th>
            <th>Format</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groupedData as $status => $statuses)
            @php
                $maxStatusCount = $statuses->max('status_count');
                $maxStatusFormats = $statuses->filter(function ($item) use ($maxStatusCount) {
                    return $item->status_count == $maxStatusCount;
                })->pluck('format')->unique()->implode(', ');
            @endphp

            <tr>
                <td>{{ $status }}</td>
                <td>{{ $maxStatusCount }}</td>
                <td>{{ $maxStatusFormats }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>








</div>
