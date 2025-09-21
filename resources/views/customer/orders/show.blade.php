@extends('layouts.app')

@section('content')
<div class="px-6 py-8">
    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                    <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="px-3 py-1 text-sm font-medium rounded-full 
                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                       ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                       ($order->status === 'ready_for_pickup' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                    Status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
                <a href="{{ route('customer.orders') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    ← Back to Orders
                </a>
            </div>
        </div>
        
        <!-- Business Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <h3 class="font-semibold text-gray-900 mb-2">Business: {{ $order->business->name ?? 'N/A' }}</h3>
            @if($order->notes)
                <p class="text-sm text-gray-600">Notes: {{ $order->notes }}</p>
            @endif
        </div>
        
        <!-- Order Progress -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium {{ $order->status === 'pending' || $order->status === 'ready_for_pickup' || $order->status === 'completed' ? 'text-blue-600' : 'text-gray-400' }}">Pending</span>
                <span class="text-sm font-medium {{ $order->status === 'ready_for_pickup' || $order->status === 'completed' ? 'text-blue-600' : 'text-gray-400' }}">Ready for Pickup</span>
                <span class="text-sm font-medium {{ $order->status === 'completed' ? 'text-blue-600' : 'text-gray-400' }}">Completed</span>
                <span class="text-sm font-medium {{ $order->status === 'cancelled' ? 'text-red-600' : 'text-gray-400' }}">Cancelled</span>
            </div>
            <div class="flex items-center">
                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-300 
                        {{ $order->status === 'pending' ? 'bg-blue-500 w-1/4' : 
                           ($order->status === 'ready_for_pickup' ? 'bg-blue-500 w-2/4' : 
                           ($order->status === 'completed' ? 'bg-green-500 w-full' : 
                           ($order->status === 'cancelled' ? 'bg-red-500 w-full' : 'bg-gray-300 w-0'))) }}"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Summary -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
            <div class="text-right">
                <div class="text-sm text-gray-500">Total Items: {{ $order->orderItems->sum('quantity') }}</div>
                <div class="text-lg font-bold text-orange-600">Total Amount: ₱{{ number_format($order->total_amount, 2) }}</div>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="space-y-4">
            @foreach($order->orderItems as $item)
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    @if($item->product && $item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-lg object-cover">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-gray-400"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $item->product->name ?? 'Product' }}</h3>
                        @if($item->selected_flavor)
                            <p class="text-sm text-gray-500">Flavor: {{ $item->selected_flavor }}</p>
                        @endif
                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × ₱{{ number_format($item->price, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₱{{ number_format($item->quantity * $item->price, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Order Total -->
        <div class="border-t border-gray-200 mt-6 pt-4">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total:</span>
                <span class="text-xl font-bold text-orange-600">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
