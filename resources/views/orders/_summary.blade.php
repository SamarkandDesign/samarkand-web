<table class="table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->product_items as $item)
        <tr>
            <td>
                @if($item->orderable)
                    <a href="{{ url($item->orderable->url) }}">{{ $item->orderable->name }}</a> x{{ $item->quantity}}
                @else
                    {{ $item->description }}
                @endif
            </td>
            <td>{{ $item->total_paid }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2"></td>
        </tr>
        @foreach ($order->shipping_items as $item)
        <tr>
            <td>
                {{ $item->description }}
            </td>
            <td>{{ $item->total_paid }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-right">Total</th>
            <th>{{ $order->amount }}</th>
        </tr>
    </tfoot>
</table>
<div class="row">
    <div class="col-md-6">
        <h3>Billing Address</h3>
        @include('partials.address', ['address' => $order->billing_address])
    </div>
    <div class="col-md-6">
        <h3>Shipping Address</h3>
        @include('partials.address', ['address' => $order->shipping_address])
    </div>
</div>
