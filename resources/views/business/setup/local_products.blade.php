@extends('layouts.app')

@section('content')
<div class="pb-12 pt-20">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-6">Set Up Your Local Products Shop</h2>
                
                <form method="POST" action="{{ route('business.setup.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information Section -->
                        <div class="col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        </div>

                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="full_name" name="full_name" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="Juan Dela Cruz" value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="birthday" class="block text-sm font-medium text-gray-700">Birthday</label>
                            <input type="date" id="birthday" name="birthday" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   value="{{ old('birthday') }}" required>
                            @error('birthday')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                            <input type="number" id="age" name="age" min="1" max="120"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="25" value="{{ old('age') }}" required>
                            @error('age')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sex" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select id="sex" name="sex" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('sex') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('sex')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <x-location-select name="personal_location" :required="true" :value="old('personal_location')">Personal Location</x-location-select>
                        </div>

                        <!-- Business Information Section -->
                        <div class="col-span-2 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
                        </div>

                        <!-- Business Name -->
                        <div class="col-span-2">
                            <label for="business_name" class="block text-sm font-medium text-gray-700">Shop Name</label>
                            <input type="text" id="business_name" name="business_name" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="e.g. Abante's Ampaw Store"
                                   value="{{ old('business_name') }}" required>
                            @error('business_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Business Type (hidden) -->
                        <input type="hidden" name="business_type" value="local_products">

                        <!-- Contact Information -->
                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="e.g. 0956 456 1525"
                                   value="{{ old('contact_number') }}" required>
                            @error('contact_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Business Email</label>
                            <input type="email" id="email" name="email" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="e.g. contact@yourbusiness.com"
                                   value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <x-location-select name="address" :required="true">Business Address</x-location-select>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div class="col-span-2">
                            <label for="website" class="block text-sm font-medium text-gray-700">Website (Optional)</label>
                            <input type="url" id="website" name="website" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="e.g. https://yourshop.com"
                                   value="{{ old('website') }}">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Business Description -->
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Business Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                      placeholder="Describe your shop, products, and any specialties (e.g., Caramel Ampaw, local snacks)."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Business Permit -->
                        <div class="col-span-2">
                            <label for="business_permit" class="block text-sm font-medium text-gray-700">Business Permit</label>
                            <input type="file" id="business_permit" name="business_permit" 
                                   class="mt-1 block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Upload a clear photo or scan of your business permit (PDF, JPG, or PNG, max 5MB)</p>
                            @error('business_permit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Complete Setup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
