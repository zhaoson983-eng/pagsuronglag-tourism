@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 -mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Orders</h1>
            <p class="text-gray-600">Track your product reservations and pickup status</p>
        </div>

        @if($orders->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <li class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($order->items->first() && $order->items->first()->product->image)
                                                <img class="h-12 w-12 rounded-lg object-cover" 
                                                     src="{{ asset('storage/' . $order->items->first()->product->image) }}" 
                                                     alt="{{ $order->items->first()->product->name }}">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-400 text-xs">No Image</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $order->items->first() ? $order->items->first()->product->name : 'Product' }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Business: {{ $order->business->name }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Quantity: {{ $order->items->sum('quantity') }} | 
                                                Total: ₱{{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}
                                            </p>
                                            @if($order->pickup_time)
                                                <p class="text-sm text-blue-600">
                                                    Pickup: {{ $order->pickup_time }}
                                                </p>
                                            @endif
                                            @if($order->notes)
                                                <p class="text-sm text-gray-500 mt-1">
                                                    Notes: {{ $order->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'ready_for_pickup') bg-green-100 text-green-800
                                        @elseif($order->status === 'completed') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->created_at->format('M d, Y g:i A') }}
                                    </p>
                                    <a href="{{ route('messages.thread', $order->business->owner_id) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800">
                                        Message Business
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
                <p class="text-gray-500 mb-4">Start exploring local products to place your first order!</p>
                <a href="{{ route('customer.products') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection