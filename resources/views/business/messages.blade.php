@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Messages</h1>
        
        @if(isset($threads) && $threads->count() > 0)
            <div class="space-y-4">
                @foreach($threads as $message)
                    @php
                        $otherUser = $message->sender_id == auth()->id() ? $message->receiver : $message->sender;
                    @endphp
                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-lg">{{ $otherUser->name }}</h3>
                            <small class="text-gray-500">{{ $message->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="text-gray-600 mb-2 whitespace-pre-line">{{ Str::limit($message->content, 100) }}</p>
                        @if($message->order_id)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Order #{{ $message->order_id }}</span>
                        @endif
                        <div class="mt-3">
                            <a href="{{ route('messages.thread', $otherUser) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                View Conversation
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg">No messages yet</p>
                <p class="text-gray-400">Order confirmations and customer messages will appear here</p>
            </div>
        @endif
    </div>
</div>
@endsection