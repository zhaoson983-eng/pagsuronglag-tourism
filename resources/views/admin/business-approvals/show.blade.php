@extends('layouts.app')

@section('title', 'Review Business: ' . $business->business_name)

@section('content')
<div class="container mx-auto px-4 py-8 -mt-20">
    <div class="mb-6">
        <a href="{{ route('admin.business-approvals.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Approvals
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $business->business_name }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        {{ ucfirst($business->business_type) }} • 
                        <span class="font-medium">{{ ucfirst($business->status) }}</span>
                        @if($business->is_approved && $business->is_published)
                            <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                Published
                            </span>
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    @if($business->is_approved)
                        <form action="{{ route('admin.business-approvals.toggle-publish', $business) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white {{ $business->is_published ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-blue-600 hover:bg-blue-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $business->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>
                    @endif
                    
                    @if($business->is_rejected)
                        <form action="{{ route('admin.business-approvals.approve', $business) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Approve Now
                            </button>
                        </form>
                    @endif
                    
                    @if(!$business->is_rejected)
                        <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Reject
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rejection Form (Hidden by default) -->
        <div id="reject-form" class="hidden px-4 py-5 sm:px-6 border-b border-red-200 bg-red-50">
            <form action="{{ route('admin.business-approvals.reject', $business) }}" method="POST">
                @csrf
                <div>
                    <label for="rejection_reason" class="block text-sm font-medium text-red-700">
                        Reason for Rejection
                    </label>
                    <div class="mt-1">
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" required>{{ old('rejection_reason', $business->rejection_reason) }}</textarea>
                        <p class="mt-2 text-sm text-gray-500">
                            Please provide a clear reason for rejection to help the business owner make the necessary changes.
                        </p>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('reject-form').classList.add('hidden')" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Confirm Rejection
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <!-- Business Information -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Business Information
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="mb-2">
                            <span class="font-medium">Business Name:</span> {{ $business->business_name }}
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Business Type:</span> {{ ucfirst(str_replace('_', ' ', $business->business_type)) }}
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Description:</span> {{ $business->description }}
                        </div>
                    </dd>
                </div>

                <!-- Contact Information -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Contact Information
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="mb-2">
                            <span class="font-medium">Contact Person:</span> {{ $business->user->name }}
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Email:</span> {{ $business->user->email }}
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Phone:</span> {{ $business->contact_number }}
                        </div>
                        @if($business->website)
                            <div class="mb-2">
                                <span class="font-medium">Website:</span> 
                                <a href="{{ $business->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ $business->website }}
                                </a>
                            </div>
                        @endif
                        @if($business->facebook_page)
                            <div class="mb-2">
                                <span class="font-medium">Facebook:</span> 
                                <a href="{{ $business->facebook_page }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ $business->facebook_page }}
                                </a>
                            </div>
                        @endif
                    </dd>
                </div>

                <!-- Business Address -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Business Address
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div>{{ $business->address }}</div>
                        <div>{{ $business->city }}, {{ $business->province }}</div>
                        <div>Postal Code: {{ $business->postal_code }}</div>
                    </dd>
                </div>

                <!-- Business Documents -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Business Documents
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <!-- Business Permit -->
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700">Business Permit / Mayor's Permit</h4>
                            <div class="mt-1 flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('admin.business-approvals.download', [$business, 'permit']) }}" class="ml-2 text-blue-600 hover:text-blue-800">
                                    Download Business Permit
                                </a>
                            </div>
                            @php
                                $permitExt = strtolower(pathinfo($business->business_permit_path ?? '', PATHINFO_EXTENSION));
                                $isImage = in_array($permitExt, ['jpg','jpeg','png','gif','webp','bmp']);
                            @endphp
                            @if($isImage)
                                <div class="mt-2">
                                    <button type="button" onclick="openPermitModal()" class="group relative inline-block">
                                        <img src="{{ $businessPermitUrl }}" alt="Business Permit Preview"
                                             class="w-40 h-40 object-contain border border-gray-200 rounded-md shadow-sm cursor-zoom-in">
                                        <span class="absolute -top-2 -left-2 text-gray-500 bg-white/80 rounded-full p-1 shadow">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <!-- Fullscreen Modal -->
                                <div id="permit-modal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
                                    <button type="button" aria-label="Close" onclick="closePermitModal()" class="absolute top-4 right-4 text-white text-2xl font-bold">
                                        &times;
                                    </button>
                                    <div class="max-w-6xl max-h-[90vh] p-4">
                                        <img src="{{ $businessPermitUrl }}" alt="Business Permit Fullscreen" class="w-auto max-w-full h-auto max-h-[85vh] rounded-md shadow-lg">
                                    </div>
                                </div>
                            @else
                                <div class="mt-2 text-sm text-gray-500">
                                    Preview not available for this file type ({{ strtoupper($permitExt) }}). Use the download link above to view the document.
                                </div>
                            @endif
                        </div>

                        <!-- Additional Licenses -->
                        @if(!empty($licenses) && count($licenses) > 0)
                            <div class="mt-6">
                                <h4 class="font-medium text-gray-700">Additional Licenses</h4>
                                <ul class="mt-2 border border-gray-200 rounded-md divide-y divide-gray-200">
                                    @foreach($licenses as $index => $license)
                                        <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                            <div class="w-0 flex-1 flex items-center">
                                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-2 flex-1 w-0 truncate">
                                                    {{ $license['name'] }}
                                                </span>
                                            </div>
                                            <div class="ml-4 flex-shrink-0
                                            ">
                                                <a href="{{ route('admin.business-approvals.download', [$business, 'license', $index]) }}" class="font-medium text-blue-600 hover:text-blue-500">
                                                    Download
                                                </a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </dd>
                </div>

                <!-- Status Information -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Status Information
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="mb-2">
                            <span class="font-medium">Current Status:</span> 
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $business->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $business->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $business->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($business->status) }}
                            </span>
                        </div>
                        @if($business->approved_at)
                            <div class="mb-2">
                                <span class="font-medium">Approved On:</span> 
                                {{ $business->approved_at->format('M d, Y h:i A') }}
                            </div>
                            @if($business->approver)
                                <div class="mb-2">
                                    <span class="font-medium">Approved By:</span> 
                                    {{ $business->approver->name }}
                                </div>
                            @endif
                        @endif
                        @if($business->is_rejected && $business->rejection_reason)
                            <div class="mt-2 p-3 bg-red-50 rounded-md">
                                <h4 class="text-sm font-medium text-red-800">Reason for Rejection:</h4>
                                <p class="mt-1 text-sm text-red-700">{{ $business->rejection_reason }}</p>
                            </div>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
        @if($business->is_pending || $business->is_rejected)
            <form action="{{ route('admin.business-approvals.approve', $business) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Approve Business
                </button>
            </form>
        @endif
        
        @if(!$business->is_rejected)
            <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                Reject Business
            </button>
        @endif
        
        <a href="{{ route('admin.business-approvals.index') }}" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Back to List
        </a>
    </div>
</div>

@push('scripts')
<script>
    // Toggle rejection reason field when reject button is clicked
    function toggleRejectForm() {
        const form = document.getElementById('reject-form');
        form.classList.toggle('hidden');
    }
    
    // If there are validation errors, show the rejection form
    @if($errors->has('rejection_reason'))
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('reject-form').classList.remove('hidden');
        });
    @endif

    // Fullscreen permit modal controls
    function openPermitModal() {
        const modal = document.getElementById('permit-modal');
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePermitModal() {
        const modal = document.getElementById('permit-modal');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection
