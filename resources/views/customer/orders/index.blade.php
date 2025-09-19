@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">My Orders</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="border rounded-lg p-4 shadow-sm bg-white">
                    <!-- Order Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                                    <span class="px-2 py-0.5 text-xs rounded-full 
                                        {{ $order->status === 'pending' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $order->status === 'ready_for_pickup' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                    ">
                                        Status: {{ ucfirst($order->status) }}
                                    </span>
                                    @if($order->updated_at->gt($order->created_at))
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-700">Updated {{ $order->updated_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('customer.orders.show', $order) }}"
                               class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                Order Details
                            </a>
                            @if($order->status === 'pending')
                                <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-100 text-red-600 text-sm rounded hover:bg-red-200 transition">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Status Progress Bar -->
                    <div class="mb-4">
                        <!-- Status Labels -->
                        <div class="flex justify-between text-xs font-medium text-gray-500 mb-2">
                            <span>Pending</span>
                            <span>Ready for Pickup</span>
                            <span>Completed</span>
                            <span>Cancelled</span>
                        </div>

                        <!-- Progress Line -->
                        <div class="relative">
                            <!-- Background Line -->
                            <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 transform -translate-y-1/2"></div>

                            <!-- Filled Progress -->
                            <div class="absolute top-1/2 left-0 h-1 transform -translate-y-1/2 transition-all duration-500 rounded-full"
                                 style="
                                    @if($order->status === 'cancelled') 
                                        width: 100%; background-color: #ef4444 
                                    @elseif($order->status === 'completed') 
                                        width: 66%; background-color: #10b981 
                                    @elseif($order->status === 'ready_for_pickup') 
                                        width: 33%; background-color: #f59e0b 
                                    @else 
                                        width: 33%; background-color: #3b82f6 
                                    @endif">
                            </div>

                            <!-- Status Dots -->
                            <div class="relative flex justify-between">
                                <!-- Pending -->
                                <div class="w-3 h-3 rounded-full 
                                    @if($order->status === 'pending') bg-blue-500 
                                    @elseif($order->status === 'ready_for_pickup' || $order->status === 'completed') bg-yellow-500 
                                    @elseif($order->status === 'cancelled') bg-red-500 
                                    @else bg-gray-300 @endif">
                                </div>

                                <!-- Ready for Pickup -->
                                <div class="w-3 h-3 rounded-full 
                                    @if($order->status === 'ready_for_pickup' || $order->status === 'completed') bg-yellow-500 
                                    @elseif($order->status === 'cancelled') bg-red-500 
                                    @else bg-gray-300 @endif">
                                </div>

                                <!-- Completed -->
                                <div class="w-3 h-3 rounded-full 
                                    @if($order->status === 'completed') bg-green-500 
                                    @elseif($order->status === 'cancelled') bg-red-500 
                                    @else bg-gray-300 @endif">
                                </div>

                                <!-- Cancelled -->
                                <div class="w-3 h-3 rounded-full 
                                    @if($order->status === 'cancelled') bg-red-500 
                                    @else bg-gray-300 @endif">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold text-gray-800">Total Items:</span>
                            <span class="font-bold text-gray-800">{{ $order->orderItems->sum('quantity') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-800">Total Amount:</span>
                            <span class="font-bold text-orange-500 text-lg">₱{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Product List -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-start space-x-3">
                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                     alt="{{ $item->product->name }}"
                                     class="h-12 w-12 rounded-lg object-cover"
                                     onerror="this.src='https://via.placeholder.com/64x64?text=No+Image'">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × ₱{{ number_format($item->price, 2) }}</p>
                                    @if($item->selected_flavor)
                                        <p class="text-xs text-gray-500">Flavor: {{ $item->selected_flavor }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Total:</strong> ₱{{ number_format($order->total_amount ?? $order->orderItems->sum(fn($i) => $i->price * $i->quantity), 2) }}</p>
                        @if($order->pickup_time)
                            <p><strong>Pickup Time:</strong> {{ $order->pickup_time }}</p>
                        @endif
                        @if($order->notes)
                            <p><strong>Notes:</strong> {{ $order->notes }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection