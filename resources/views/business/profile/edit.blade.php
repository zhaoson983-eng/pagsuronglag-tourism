@extends('layouts.app')

@section('title', 'Edit Business Profile')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Edit Business Profile
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Update your business information and documents.
                    </p>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $business->isApproved() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $business->isApproved() ? 'Approved' : 'Pending Approval' }}
                    </span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('business.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-8 divide-y divide-gray-200">
                <!-- Business Information -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Business Information</h3>
                        <p class="mt-1 text-sm text-gray-500">Basic information about your business.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Business Name -->
                        <div class="sm:col-span-4">
                            <label for="business_name" class="block text-sm font-medium text-gray-700">
                                Business Name <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" name="business_name" id="business_name" 
                                    value="{{ old('business_name', $business->business_name) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                @error('business_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Business Type (Display Only) -->
                        <div class="sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700">Business Type</label>
                            <div class="mt-1">
                                <p class="text-sm text-gray-900">{{ $business->business_type_label }}</p>
                                <p class="text-xs text-gray-500 mt-1">Contact support to change your business type.</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Business Description <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="3" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('description', $business->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="pt-8">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Contact Information</h3>
                        <p class="mt-1 text-sm text-gray-500">How can customers reach you?</p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Contact Number -->
                        <div class="sm:col-span-4">
                            <label for="contact_number" class="block text-sm font-medium text-gray-700">
                                Contact Number <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="tel" name="contact_number" id="contact_number" 
                                    value="{{ old('contact_number', $business->contact_number) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                @error('contact_number')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Website -->
                        <div class="sm:col-span-4">
                            <label for="website" class="block text-sm font-medium text-gray-700">
                                Website
                            </label>
                            <div class="mt-1">
                                <input type="url" name="website" id="website"
                                    value="{{ old('website', $business->website) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                @error('website')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Facebook Page -->
                        <div class="sm:col-span-4">
                            <label for="facebook_page" class="block text-sm font-medium text-gray-700">
                                Facebook Page
                            </label>
                            <div class="mt-1">
                                <input type="url" name="facebook_page" id="facebook_page"
                                    value="{{ old('facebook_page', $business->facebook_page) }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="https://facebook.com/yourpage">
                                @error('facebook_page')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Address -->
                <div class="pt-8">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Business Address</h3>
                        <p class="mt-1 text-sm text-gray-500">Where is your business located?</p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Address Line 1 -->
                        <div class="sm:col-span-6">
                            <label for="address" class="block text-sm font-medium text-gray-700">
                                Street Address <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" name="address" id="address" 
                                    value="{{ old('address', $business->address) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- City -->
                        <div class="sm:col-span-2">
                            <label for="city" class="block text-sm font-medium text-gray-700">
                                City <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" name="city" id="city" 
                                    value="{{ old('city', $business->city) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Province -->
                        <div class="sm:col-span-2">
                            <label for="province" class="block text-sm font-medium text-gray-700">
                                Province <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" name="province" id="province" 
                                    value="{{ old('province', $business->province) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                @error('province')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Postal Code -->
                        <div class="sm:col-span-2">
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">
                                Postal Code <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input type="text" name="postal_code" id="postal_code" 
                                    value="{{ old('postal_code', $business->postal_code) }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                @error('postal_code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Documents -->
                <div class="pt-8">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Business Documents</h3>
                        <p class="mt-1 text-sm text-gray-500">Update your business documents if needed.</p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Current Business Permit -->
                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700">
                                Current Business Permit
                            </label>
                            <div class="mt-1">
                                <div class="flex items-center">
                                    <a href="{{ Storage::url($business->business_permit_path) }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="fas fa-file-pdf mr-1"></i> View Current Permit
                                    </a>
                                    <span class="ml-2 text-sm text-gray-500">(Upload a new file below to update)</span>
                                </div>
                            </div>
                        </div>

                        <!-- New Business Permit -->
                        <div class="sm:col-span-6">
                            <label for="business_permit" class="block text-sm font-medium text-gray-700">
                                New Business Permit (Leave blank to keep current)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="business_permit" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="business_permit" name="business_permit" type="file"
                                                class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PDF, JPG, or PNG up to 5MB
                                    </p>
                                </div>
                            </div>
                            @error('business_permit')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Licenses -->
                        @if(!empty($business->licenses) && count($business->licenses) > 0)
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">
                                    Current Licenses
                                </label>
                                <div class="mt-1">
                                    <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                        @foreach($business->licenses as $index => $license)
                                            <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                                <div class="w-0 flex-1 flex items-center">
                                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="ml-2 flex-1 w-0 truncate">
                                                        {{ $license['original_name'] }}
                                                    </span>
                                                </div>
                                                <div class="ml-4 flex-shrink-0">
                                                    <a href="{{ Storage::url($license['path']) }}" target="_blank" class="font-medium text-blue-600 hover:text-blue-500">
                                                        View
                                                    </a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Upload new files below to add to your existing licenses.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Additional Licenses -->
                        <div class="sm:col-span-6">
                            <label for="other_licenses" class="block text-sm font-medium text-gray-700">
                                Additional Licenses (Optional)
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="other_licenses" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload files</span>
                                            <input id="other_licenses" name="other_licenses[]" type="file" multiple
                                                class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PDF, JPG, or PNG up to 5MB each
                                    </p>
                                </div>
                            </div>
                            @error('other_licenses.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Message -->
                @if($business->isRejected() && $business->rejection_reason)
                    <div class="bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <strong>Profile Rejected:</strong> {{ $business->rejection_reason }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Form Actions -->
            <div class="pt-5">
                <div class="flex justify-between">
                    <div>
                        @if($business->is_published)
                            <form action="{{ route('business.unpublish') }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-eye-slash mr-2"></i> Unpublish Business
                                </button>
                            </form>
                        @else
                            <form action="{{ route('business.publish') }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 {{ $business->isApproved() ? '' : 'opacity-50 cursor-not-allowed' }}"
                                    {{ $business->isApproved() ? '' : 'disabled' }}>
                                    <i class="fas fa-upload mr-2"></i> Publish Business
                                </button>
                            </form>
                            @if(!$business->isApproved())
                                <p class="mt-1 text-xs text-gray-500">Your business must be approved before publishing.</p>
                            @endif
                        @endif
                    </div>
                    <div class="flex">
                        <a href="{{ route('business.dashboard') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // File upload preview
    document.addEventListener('DOMContentLoaded', function() {
        // Business permit preview
        const businessPermitInput = document.getElementById('business_permit');
        if (businessPermitInput) {
            businessPermitInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const fileName = e.target.files[0].name;
                    const label = businessPermitInput.previousElementSibling?.querySelector('label[for="business_permit"]');
                    if (label) {
                        label.textContent = 'New file selected: ' + fileName;
                    }
                }
            });
        }

        // Other licenses preview
        const otherLicensesInput = document.getElementById('other_licenses');
        if (otherLicensesInput) {
            otherLicensesInput.addEventListener('change', function(e) {
                const fileCount = e.target.files.length;
                if (fileCount > 0) {
                    const label = otherLicensesInput.previousElementSibling?.querySelector('label[for="other_licenses"]');
                    if (label) {
                        label.textContent = `${fileCount} file${fileCount !== 1 ? 's' : ''} selected`;
                    }
                }
            });
        }
    });
</script>
@endpush
