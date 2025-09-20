@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">{{ isset($product) ? 'Edit' : 'Add New' }} Product</h1>

    <form method="POST" action="{{ isset($product) ? route('business.product.update', $product) : route('business.product.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Product Name *</label>
                    <input type="text" name="name" id="name" value="{{ $product->name ?? old('name') }}" 
                           class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Price *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2">₱</span>
                        <input type="number" step="0.01" name="price" id="price" value="{{ $product->price ?? old('price') }}" 
                               class="w-full pl-8 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="stock_limit">Stock Limit *</label>
                    <input type="number" name="stock_limit" id="stock_limit" min="0" 
                           value="{{ $product->stock_limit ?? old('stock_limit', 0) }}" 
                           class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Maximum quantity available for this product</p>
                    @error('stock_limit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="current_stock">Current Stock *</label>
                    <input type="number" name="current_stock" id="current_stock" min="0" 
                           value="{{ $product->current_stock ?? old('current_stock', 0) }}" 
                           class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Current available quantity</p>
                    @error('current_stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="flavors">Flavors/Variants</label>
                    <input type="text" name="flavors" id="flavors" 
                           value="{{ $product->flavors ?? old('flavors') }}" 
                           class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="e.g. Original, Spicy, Sweet">
                    <p class="text-xs text-gray-500 mt-1">Separate multiple flavors with commas</p>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Product Image *</label>
                    <div class="mt-1 flex items-center">
                        <div class="w-48 h-48 border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center overflow-hidden">
                            <img id="image-preview" src="{{ isset($product) && $product->image ? asset('storage/' . $product->image) : '#' }}" 
                                 alt="Preview" class="w-full h-full object-cover {{ !isset($product) || !$product->image ? 'hidden' : '' }}">
                            <span id="no-image-text" class="text-gray-400 {{ isset($product) && $product->image ? 'hidden' : '' }}">
                                No image selected
                            </span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="file" name="image" id="image" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               onchange="previewImage(this)" {{ !isset($product) ? 'required' : '' }}>
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 2MB</p>
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                    <textarea name="description" id="description" rows="5" 
                              class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $product->description ?? old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a href="{{ route('business.my-shop') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                Cancel
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                {{ isset($product) ? 'Update' : 'Save' }} Product
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const noImageText = document.getElementById('no-image-text');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (noImageText) noImageText.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
            if (noImageText) noImageText.classList.remove('hidden');
        }
    }

    // Validate stock limits
    document.addEventListener('DOMContentLoaded', function() {
        const stockLimitInput = document.getElementById('stock_limit');
        const currentStockInput = document.getElementById('current_stock');
        
        function validateStocks() {
            const stockLimit = parseInt(stockLimitInput.value) || 0;
            const currentStock = parseInt(currentStockInput.value) || 0;
            
            if (currentStock > stockLimit) {
                currentStockInput.setCustomValidity('Current stock cannot exceed stock limit');
            } else {
                currentStockInput.setCustomValidity('');
            }
        }
        
        stockLimitInput.addEventListener('change', validateStocks);
        currentStockInput.addEventListener('change', validateStocks);
    });
</script>
@endpush
@endsection