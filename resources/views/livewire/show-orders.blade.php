<div>
    <input type="range" wire:model="week" min="0" max="{{ count($weeks) - 1 }}" value="{{$week}}">
    <select wire:model="location">
        <option value="0">Nederland</option>
        @foreach($locations as $location)
            <option value="{{ $location->id }}">{{ $location->name }}</option>
        @endforeach
    </select>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Koper</th>
            <th>Datum / tijd</th>
            <th>Product</th>
            <th>Vestiging</th>
            <th>Verkoper</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->name }}</td>
                <td>{{ $order->created_at }}</td>
                <td>{{ $order->product }}</td>
                <td>{{ $order->location->name }}</td>
                <td>{{ $order->seller }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
