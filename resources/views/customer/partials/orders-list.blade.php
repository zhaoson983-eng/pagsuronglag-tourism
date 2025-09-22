@php
    $orders = auth()->user()->orders()
        ->with(['items.product'])
        ->latest()
        ->take(5)
        ->get();
@endphp

@if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
            <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-medium text-sm text-gray-900">
                            Order #{{ $order->id }}
                        </h4>
                        <p class="text-xs text-gray-500">
                            {{ $order->created_at->format('M d, Y') }}
                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $order->status === 'pending' ? 'bg-blue-100 text-blue-800' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="mt-2 text-sm">
                    @if($order->items->count() > 0)
                        <p class="text-gray-600 truncate">
                            {{ $order->items->first()->product->name ?? 'Product' }}
                            @if($order->items->count() > 1)
                                +{{ $order->items->count() - 1 }} more
                            @endif
                        </p>
                    @endif
                    <p class="font-medium text-gray-900 mt-1">
                        ₱{{ number_format($order->total_amount, 2) }}
                    </p>
                </div>
                <a href="{{ route('customer.orders.show', $order) }}" 
                   class="mt-2 inline-block text-xs text-blue-600 hover:text-blue-800 hover:underline">
                    View Details
                </a>
            </div>
        @endforeach
    </div>
    
    <div class="mt-4 text-center">
        <a href="{{ route('customer.orders.index') }}" 
           class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
            View All Orders
        </a>
    </div>
@else
    <div class="text-center py-6">
        <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
            <i class="fas fa-shopping-bag text-gray-400"></i>
        </div>
        <p class="text-sm text-gray-500">No orders yet</p>
        <a href="{{ route('customer.products') }}" 
           class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-800 hover:underline">
            Start Shopping
        </a>
    </div>
@endif
