@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-3xl">
    <!-- Chat Header -->
    <div class="bg-white shadow rounded-t-lg p-4 flex items-center">
        <a href="{{ url()->previous() }}" class="text-blue-600 mr-3">←</a>
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full overflow-hidden">
                @if($user->profile && $user->profile->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile->profile_picture) }}"
                         alt="{{ $user->name }}"
                         class="h-full w-full object-cover">
                @else
                    <div class="h-full w-full bg-blue-500 flex items-center justify-center">
                        <span class="text-white font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="ml-3">
                <h2 class="font-semibold text-lg">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">
                    {{ ucfirst(str_replace('_', ' ', $user->role ?? '')) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="bg-gray-100 p-4 h-[60vh] overflow-y-auto space-y-3" id="messages-container">
        @forelse($messages as $msg)
            <div class="flex {{ $msg->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs px-4 py-2 rounded-lg 
                    {{ $msg->sender_id == auth()->id() ? 'bg-blue-500 text-white' : 'bg-white border' }}">
                    <div>{!! nl2br(e($msg->content)) !!}</div>

                    @if($msg->order)
                        <div class="mt-2 text-xs italic border-t pt-1 
                            {{ $msg->sender_id == auth()->id() ? 'text-blue-100' : 'text-gray-600' }}">
                            🔗 Refers to Order #{{ $msg->order->id }}
                        </div>
                    @endif

                    <div class="text-xs opacity-70 mt-1">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center py-4">Start the conversation!</p>
        @endforelse
    </div>

    <!-- Send Form -->
    <form action="{{ route('messages.send') }}" method="POST" class="bg-white p-3 flex items-center space-x-2 border-t">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
        <textarea 
            name="content" 
            rows="1" 
            class="flex-1 border rounded-full px-4 py-2 resize-none focus:outline-none"
            placeholder="Type a message..."
            required
            autofocus></textarea>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full">
            Send
        </button>
    </form>
</div>

<!-- Auto-scroll to bottom -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
});
</script>
@endsection