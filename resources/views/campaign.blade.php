<x-app-layout>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Koper</th>
            <th>Datum / tijd</th>
            <th>Product</th>
            <th>Vestiging / verkoper</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->created_at }}</td>
                <td>{{ $order->product }}</td>
                <td>{{ $order->seller }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-app-layout>
