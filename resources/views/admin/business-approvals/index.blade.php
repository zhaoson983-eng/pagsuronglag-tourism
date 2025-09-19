@extends('layouts.app')

@section('title', 'Business Approvals')

@section('content')
<div class="container mx-auto px-4 py-8 -mt-20">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Business Approvals</h1>
        <div class="flex space-x-4">
            <a href="#pending" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Pending ({{ $pendingBusinesses->total() }})
            </a>
            <a href="#approved" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Approved ({{ $approvedBusinesses->total() }})
            </a>
            <a href="#rejected" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Rejected ({{ $rejectedBusinesses->total() }})
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Pending Businesses -->
    <div id="pending" class="mb-12">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Pending Approval</h2>
            <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                {{ $pendingBusinesses->total() }} Pending
            </span>
        </div>

        @if($pendingBusinesses->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">No businesses pending approval.</p>
            </div>
        @else
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($pendingBusinesses as $business)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            <p class="text-lg font-medium text-blue-600 truncate">
                                                {{ $business->business_name }}
                                            </p>
                                            <span class="ml-2 px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($business->business_type) }}
                                            </span>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $business->user->name }}
                                                </p>
                                                <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                                    </svg>
                                                    {{ $business->user->email }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                </svg>
                                                <p>
                                                    Applied on <time datetime="{{ $business->created_at->format('Y-m-d') }}">{{ $business->created_at->format('M d, Y') }}</time>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex items-center space-x-3">
                                        <form action="{{ route('admin.business-approvals.approve', $business) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>
                                        <button type="button"
                                            onclick="document.getElementById('reject-form-{{ $business->id }}').classList.toggle('hidden')"
                                            class="px-3 py-1 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                            Decline
                                        </button>
                                        <a href="{{ route('admin.business-approvals.show', $business) }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                            Review
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Inline Reject Form -->
                            <div id="reject-form-{{ $business->id }}" class="hidden px-4 pb-4 sm:px-6">
                                <form action="{{ route('admin.business-approvals.reject', $business) }}" method="POST" class="bg-red-50 border border-red-200 rounded-md p-4">
                                    @csrf
                                    <label for="rejection_reason_{{ $business->id }}" class="block text-sm font-medium text-red-700">Reason for Decline</label>
                                    <textarea id="rejection_reason_{{ $business->id }}" name="rejection_reason" rows="2" class="mt-1 w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required></textarea>
                                    <div class="mt-3 flex justify-end space-x-2">
                                        <button type="button" class="px-3 py-1 text-sm rounded-md border" onclick="document.getElementById('reject-form-{{ $business->id }}').classList.add('hidden')">Cancel</button>
                                        <button type="submit" class="px-3 py-1 text-sm rounded-md text-white bg-red-600 hover:bg-red-700">Confirm Decline</button>
                                    </div>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @if($pendingBusinesses->hasPages())
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                        {{ $pendingBusinesses->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Approved Businesses -->
    <div id="approved" class="mb-12">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Approved Businesses</h2>
            <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                {{ $approvedBusinesses->total() }} Approved
            </span>
        </div>

        @if($approvedBusinesses->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">No approved businesses yet.</p>
            </div>
        @else
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($approvedBusinesses as $business)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            <p class="text-lg font-medium text-green-600 truncate">
                                                {{ $business->business_name }}
                                            </p>
                                            <span class="ml-2 px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                {{ ucfirst($business->business_type) }}
                                            </span>
                                            @if($business->is_published)
                                                <span class="ml-2 px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                    Published
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $business->user->name }}
                                                </p>
                                                <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                                    </svg>
                                                    {{ $business->user->email }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                </svg>
                                                <p>
                                                    Approved on <time datetime="{{ $business->approved_at->format('Y-m-d') }}">{{ $business->approved_at->format('M d, Y') }}</time>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex space-x-2">
                                        <form action="{{ route('admin.business-approvals.toggle-publish', $business) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-sm font-medium {{ $business->is_published ? 'text-yellow-600 hover:text-yellow-500' : 'text-blue-600 hover:text-blue-500' }}">
                                                {{ $business->is_published ? 'Unpublish' : 'Publish' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.business-approvals.show', $business) }}" class="text-sm font-medium text-gray-600 hover:text-gray-500">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @if($approvedBusinesses->hasPages())
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                        {{ $approvedBusinesses->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Rejected Businesses -->
    <div id="rejected">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Rejected Businesses</h2>
            <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                {{ $rejectedBusinesses->total() }} Rejected
            </span>
        </div>

        @if($rejectedBusinesses->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">No rejected businesses.</p>
            </div>
        @else
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($rejectedBusinesses as $business)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            <p class="text-lg font-medium text-red-600 truncate">
                                                {{ $business->business_name }}
                                            </p>
                                            <span class="ml-2 px-2.5 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                {{ ucfirst($business->business_type) }}
                                            </span>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $business->user->name }}
                                                </p>
                                                <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                                    </svg>
                                                    {{ $business->user->email }}
                                                </p>
                                            </div>
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                </svg>
                                                <p>
                                                    Rejected on <time datetime="{{ $business->updated_at->format('Y-m-d') }}">{{ $business->updated_at->format('M d, Y') }}</time>
                                                </p>
                                            </div>
                                        </div>
                                        @if($business->rejection_reason)
                                            <div class="mt-2">
                                                <p class="text-sm text-red-600">
                                                    <span class="font-medium">Reason:</span> {{ $business->rejection_reason }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ route('admin.business-approvals.show', $business) }}" class="font-medium text-gray-600 hover:text-gray-500">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @if($rejectedBusinesses->hasPages())
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                        {{ $rejectedBusinesses->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 20,
                    behavior: 'smooth'
                });
                
                // Update URL without page reload
                history.pushState(null, '', targetId);
            }
        });
    });
    
    // Highlight the current section in the navigation
    const sections = document.querySelectorAll('div[id]');
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    window.addEventListener('scroll', () => {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (pageYOffset >= (sectionTop - 100)) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('ring-2', 'ring-offset-2');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('ring-2', 'ring-offset-2');
            }
        });
    });
    
    // Trigger scroll event on page load to highlight the current section
    window.dispatchEvent(new Event('scroll'));
</script>
@endpush
@endsection
