<table class="w-full">
    <thead>
        <tr class="border-b border-gray-300">
            <th class="text-left pr-2 py-2">Product</th>
            <th class="text-right pl-2 py-2">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->product_items as $item)
        <tr class="border-b border-gray-300">
            <td class="text-left py-2">
                @if($item->orderable)
                <p><a href="{{ url($item->orderable->url) }}">{{ $item->orderable->name }}</a></p>
                @else
                <p>{{ $item->description }}</p>
                @endif
                <p class="text-gray-600 text-sm">x{{ $item->quantity}}</p>
            </td>
            <td class="py-2 pl-2 text-right">{{ $item->total_paid }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2"></td>
        </tr>
        @foreach ($order->shipping_items as $item)
        <tr class="border-b border-gray-300">
            <td class="text-left py-2">
                {{ $item->description }}
            </td>
            <td class="py-2 pl-2 text-right">{{ $item->total_paid }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-right py-2 pl-2 text-right">Total</th>
            <th class="py-2 pl-2 text-right">
                <p>{{ $order->amount }}</p>
                @if(config('shop.vat_rate'))
                <p class="text-gray-600 text-sm font-normal">Includes {{ $order->vatAmount }} VAT</p>
                @endif
            </th>
        </tr>
    </tfoot>
</table>
<div class="flex -mx-4">
    <div class="w-1/2 px-4 vspace-4">
        <h3 class="text-xl">Billing Address</h3>
        @include('partials.address', ['address' => $order->billing_address])
    </div>
    <div class="w-1/2 px-4  vspace-4">
        <h3 class="text-xl">Shipping Address</h3>
        @include('partials.address', ['address' => $order->shipping_address])
    </div>
</div>