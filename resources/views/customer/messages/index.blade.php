@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Messages</h1>
        <p class="text-gray-600">Chat with business owners about your orders</p>
    </div>

    @if($threads->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($threads as $thread)
                    @php
                        // Determine the other user in the conversation
                        $otherUser = $thread->sender_id == auth()->id() 
                            ? $thread->receiver 
                            : $thread->sender;

                        // Get the last message (assuming $thread has a last_id or use latest())
                        $lastMessage = \App\Models\Message::where('id', $thread->last_id ?? $thread->id)
                            ->first();
                    @endphp

                    @if($otherUser)
                        <li class="px-6 py-4 hover:bg-gray-50 transition">
                            <a href="{{ route('messages.thread', $otherUser) }}" class="block">
                                <div class="flex items-center space-x-3">
                                    <!-- Profile Picture -->
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden">
                                        @if($otherUser->profile && $otherUser->profile->profile_picture)
                                            <img src="{{ asset('storage/' . $otherUser->profile->profile_picture) }}"
                                                 alt="{{ $otherUser->name }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-white font-medium text-sm">
                                                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- User Info & Message Preview -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $otherUser->name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $lastMessage?->created_at->diffForHumans() ?? '' }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ $lastMessage?->content ? strip_tags($lastMessage->content) : 'No messages yet' }}
                                        </p>

                                        <!-- Show business name if available -->
                                        @if($otherUser->business)
                                            <p class="text-xs text-blue-600 font-medium">
                                                {{ $otherUser->business->name }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No messages yet</h3>
            <p class="text-gray-500 mb-4">Start a conversation when you place an order with a business!</p>
            <a href="{{ route('customer.products') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Browse Products
            </a>
        </div>
    @endif
</div>
@endsection