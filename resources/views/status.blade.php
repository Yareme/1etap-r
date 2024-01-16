<div>
    <h1>Status</h1>

    <table>
        <thead>
        <tr>
            <th>Status</th>
            <th>Status Counter</th>
            <th>Format</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $statusItem)
            <tr>
                <td>{{ $statusItem->status }}</td>
                <td>{{ $statusItem->status_count }}</td>
                <td>{{ $statusItem->format }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
