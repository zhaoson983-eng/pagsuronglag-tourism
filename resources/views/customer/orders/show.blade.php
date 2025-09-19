@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h2>

    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <h3 class="text-lg font-semibold">Business: {{ $order->business->name ?? 'N/A' }}</h3>
        <p>Status: <span class="font-medium">{{ ucfirst($order->status) }}</span></p>
        <p>Placed on: {{ $order->created_at->format('M d, Y h:i A') }}</p>
        @if($order->notes)
            <p>Notes: {{ $order->notes }}</p>
        @endif

        <h4 class="mt-4 font-semibold">Items</h4>
        <ul class="list-disc pl-5">
            @foreach($order->orderItems as $item)
                <li>
                    {{ $item->quantity }} × {{ $item->product->name }} – ₱{{ number_format($item->price, 2) }}
                    @if($item->selected_flavor)
                        <span class="text-gray-500">(Flavor: {{ $item->selected_flavor }})</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Messages Section -->
    <div class="bg-white p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-3">Messages</h3>

        <div class="space-y-3 mb-4 max-h-64 overflow-y-auto border p-3 rounded">
            @forelse($order->messages as $message)
                <div class="{{ $message->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                    <p class="inline-block px-3 py-2 rounded-lg
                        {{ $message->sender_id == auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                        {{ $message->content }}
                    </p>
                    <small class="block text-gray-500 text-xs">
                        {{ $message->created_at->format('M d, Y h:i A') }}
                    </small>
                </div>
            @empty
                <p class="text-gray-500">No messages yet.</p>
            @endforelse
        </div>

        <!-- Message Form -->
        <form method="POST" action="{{ route('customer.orders.message', $order->id) }}" class="flex gap-2">
            @csrf
            <input type="text" name="content" placeholder="Type your message..."
                   class="flex-1 border rounded-lg px-3 py-2" required>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Send
            </button>
        </form>
    </div>
</div>
@endsection
