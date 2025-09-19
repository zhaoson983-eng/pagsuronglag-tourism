@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-3xl">
    <h2 class="text-xl font-bold mb-4">Your Conversations</h2>

    @if($threads->isEmpty())
        <p class="text-gray-500">No conversations yet.</p>
    @else
        <ul class="divide-y divide-gray-200">
            @foreach($threads as $thread)
                @php
                    // Determine the other user in the conversation
                    $otherUser = $thread->sender_id == auth()->id() 
                        ? $thread->receiver 
                        : $thread->sender;

                    // Safely get the last message content
                    $lastMessage = $thread->content ? strip_tags($thread->content) : 'No message content';
                    $preview = strlen($lastMessage) > 80 
                        ? substr($lastMessage, 0, 80) . '...' 
                        : $lastMessage;
                @endphp

                @if($otherUser)
                    <li class="py-3 hover:bg-gray-50 transition">
                        <a href="{{ route('messages.thread', $otherUser->id) }}" class="block">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full overflow-hidden">
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
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-gray-800 truncate">
                                        {{ $otherUser->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 truncate">
                                        {{ $preview }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    @endif
</div>
@endsection