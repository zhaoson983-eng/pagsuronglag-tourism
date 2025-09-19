<div class="bg-white rounded-lg shadow p-4 mb-4">
    <h3 class="font-semibold mb-3">Messages</h3>
    @foreach($messages as $msg)
        <div class="mb-2 p-2 rounded {{ $msg->sender_id == auth()->id() ? 'bg-blue-50 ml-auto max-w-xs' : 'bg-gray-100 mr-auto max-w-xs' }}">
            <p class="whitespace-pre-line">{{ $msg->content ?? $msg->message ?? '' }}</p>
            <small class="text-gray-500 block">{{ $msg->created_at->diffForHumans() }}</small>
            @if($msg->order_id)
                <small class="text-blue-500 block">Order #{{ $msg->order_id }}</small>
            @endif
        </div>
    @endforeach
    <form method="POST" action="{{ route('messages.send') }}" class="mt-3">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $receiver_id }}">
        <input type="hidden" name="order_id" value="{{ $order_id ?? '' }}">
        <textarea name="content" placeholder="Type a message..." class="w-full p-2 border rounded" required></textarea>
        <button type="submit" class="mt-2 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Send</button>
    </form>
</div>