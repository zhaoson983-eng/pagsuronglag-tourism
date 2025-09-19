@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Orders</h1>

        @if($orders->isEmpty())
            <div class="text-center py-16">
                <div class="text-gray-400 text-7xl mb-6">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="text-2xl font-medium text-gray-900 mb-3">No orders yet</h3>
                <p class="text-gray-500 text-lg">Orders from customers will appear here.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Customer</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Items</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Total</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            @php
                                $total = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <ul class="space-y-1">
                                        @foreach($order->orderItems as $item)
                                            <li>{{ $item->product->name }} ×{{ $item->quantity }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">₱{{ number_format($total, 2) }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('business.orders.update.status', $order) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="border border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                            <option {{ $order->status === 'pending' ? 'selected' : '' }} value="pending">Pending</option>
                                            <option {{ $order->status === 'ready_for_pickup' ? 'selected' : '' }} value="ready_for_pickup">Ready for Pickup</option>
                                            <option {{ $order->status === 'completed' ? 'selected' : '' }} value="completed">Completed</option>
                                            <option {{ $order->status === 'cancelled' ? 'selected' : '' }} value="cancelled">Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <a href="{{ route('customer.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                        <i class="fas fa-eye mr-1"></i> View Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection