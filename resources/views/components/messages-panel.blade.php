@php
    $user = auth()->user();
    $unreadMessages = $user ? $user->unreadMessages()->count() : 0;
    $threads = $user ? $user->threads() : collect();
@endphp

<!-- Messages Panel -->
<div id="messagesPanel" class="fixed top-16 right-0 h-[calc(100vh-4rem)] w-80 bg-white shadow-lg transform transition-transform duration-300 ease-in-out translate-x-full z-40 border-l border-gray-200 flex flex-col">
    <!-- Panel Header -->
    <div class="bg-blue-600 text-white px-4 py-3 flex justify-between items-center">
        <h3 class="font-medium">Messages</h3>
        <button id="closeMessages" class="text-white hover:text-gray-200 focus:outline-none">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <!-- Messages List -->
    <div class="flex-1 overflow-y-auto">
        @if($threads->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($threads as $otherUser)
                    @php
                        $lastMessage = $otherUser->last_message ?? null;
                        $isUnread = $lastMessage && $lastMessage->receiver_id == $user->id && !$lastMessage->read_at;
                    @endphp
                    
                    <a href="{{ route('messages.thread', $otherUser) }}" class="block hover:bg-gray-50 transition-colors">
                        <div class="px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <!-- Profile Picture -->
                                <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden">
                                    @if($otherUser->profile && $otherUser->profile->profile_picture)
                                        <img src="{{ Storage::url($otherUser->profile->profile_picture) }}"
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

                                <!-- Message Preview -->
                                <div class="flex-1 min-w-0 {{ $isUnread ? 'font-medium' : '' }}">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm text-gray-900 truncate">
                                            {{ $otherUser->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                        </p>
                                    </div>
                                    <p class="text-xs text-gray-500 truncate">
                                        @if($lastMessage)
                                            {{ $lastMessage->sender_id == $user->id ? 'You: ' : '' }}
                                            {{ \Illuminate\Support\Str::limit(strip_tags($lastMessage->content), 30) }}
                                        @else
                                            No messages yet
                                        @endif
                                    </p>
                                    
                                    @if($otherUser->businessProfile)
                                        <p class="text-xs text-blue-600 font-medium mt-1">
                                            {{ $otherUser->businessProfile->business_name }}
                                        </p>
                                    @endif
                                </div>
                                
                                @if($isUnread)
                                    <span class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="p-4 text-center text-sm text-gray-500">
                <p>No messages yet</p>
                <a href="{{ route('customer.products') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                    Browse products to get started
                </a>
            </div>
        @endif
    </div>
    
    <!-- Panel Footer -->
    <div class="border-t border-gray-200 p-3 bg-gray-50">
        <a href="{{ route('customer.messages') }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
            View All Messages
        </a>
    </div>
</div>

<!-- Messages Toggle Button (Fixed on desktop) -->
<div class="fixed bottom-8 right-8 z-30 hidden md:block">
    <button id="toggleMessages" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-transform hover:scale-105 relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        @if($unreadMessages > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ $unreadMessages }}
            </span>
        @endif
    </button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messagesPanel = document.getElementById('messagesPanel');
        const toggleBtn = document.getElementById('toggleMessages');
        const closeBtn = document.getElementById('closeMessages');
        
        // Check if panel was previously open
        const isPanelOpen = localStorage.getItem('messagesPanelOpen') === 'true';
        if (isPanelOpen) {
            messagesPanel.classList.remove('translate-x-full');
            messagesPanel.classList.add('translate-x-0');
        }
        
        // Toggle panel
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                messagesPanel.classList.toggle('translate-x-full');
                messagesPanel.classList.toggle('translate-x-0');
                
                // Save state
                const isOpen = !messagesPanel.classList.contains('translate-x-full');
                localStorage.setItem('messagesPanelOpen', isOpen);
            });
        }
        
        // Close panel
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                messagesPanel.classList.add('translate-x-full');
                messagesPanel.classList.remove('translate-x-0');
                localStorage.setItem('messagesPanelOpen', false);
            });
        }
        
        // Close panel when clicking outside
        document.addEventListener('click', function(event) {
            if (!messagesPanel.contains(event.target) && 
                !toggleBtn.contains(event.target) && 
                !messagesPanel.classList.contains('translate-x-full')) {
                messagesPanel.classList.add('translate-x-full');
                messagesPanel.classList.remove('translate-x-0');
                localStorage.setItem('messagesPanelOpen', false);
            }
        });
    });
</script>
@endpush
