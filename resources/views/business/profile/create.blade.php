@extends('layouts.app')

@section('title', 'Create Business Profile')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Business Profile Setup
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Complete your business profile to start using our platform.
            </p>
        </div>
        
        <form action="{{ route('business.profile.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
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
                                <input type="text" name="business_name" id="business_name" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                @error('business_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Business Type -->
                        <div class="sm:col-span-4">
                            <label for="business_type" class="block text-sm font-medium text-gray-700">
                                Business Type <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <select id="business_type" name="business_type" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Select business type</option>
                                    <option value="local_products">Local Products Shop</option>
                                    <option value="hotel">Hotel</option>
                                    <option value="resort">Resort</option>
                                </select>
                                @error('business_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Business Description <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="3" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Tell us about your business.</p>
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
                                <input type="tel" name="contact_number" id="contact_number" required
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
                                <input type="text" name="address" id="address" required
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
                                <input type="text" name="city" id="city" required
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
                                <input type="text" name="province" id="province" required
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
                                <input type="text" name="postal_code" id="postal_code" required
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
                        <p class="mt-1 text-sm text-gray-500">Upload required business documents for verification.</p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Business Permit -->
                        <div class="sm:col-span-6">
                            <label for="business_permit" class="block text-sm font-medium text-gray-700">
                                Business Permit/Mayor's Permit <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="business_permit" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="business_permit" name="business_permit" type="file" required
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

                        <!-- Other Licenses -->
                        <div class="sm:col-span-6">
                            <label for="other_licenses" class="block text-sm font-medium text-gray-700">
                                Other Business Licenses (Optional)
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
            </div>

            <!-- Form Actions -->
            <div class="pt-5">
                <div class="flex justify-end">
                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save & Continue
                    </button>
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
                const fileName = e.target.files[0]?.name || 'No file chosen';
                const label = businessPermitInput.previousElementSibling;
                if (label) {
                    label.textContent = fileName;
                }
            });
        }

        // Other licenses preview
        const otherLicensesInput = document.getElementById('other_licenses');
        if (otherLicensesInput) {
            otherLicensesInput.addEventListener('change', function(e) {
                const fileCount = e.target.files.length;
                const label = otherLicensesInput.previousElementSibling;
                if (label) {
                    label.textContent = fileCount > 0 ? 
                        `${fileCount} file${fileCount !== 1 ? 's' : ''} selected` : 
                        'Upload files';
                }
            });
        }
    });
</script>
@endpush
