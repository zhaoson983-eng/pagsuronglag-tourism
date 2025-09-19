@extends('layouts.app')

@section('title', 'My Products')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <h1 class="text-4xl font-bold text-gray-800">My Products</h1>
            <a href="{{ route('business.product.create') }}" class="bg-green-500 text-white px-6 py-3 rounded-xl hover:bg-green-600 transition-colors duration-200 flex items-center text-lg font-medium">
                <i class="fas fa-plus mr-2"></i> Add Product
            </a>
        </div>

        @if($products && $products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($products as $product)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-xl transition-shadow duration-200">
                        <div class="h-56 bg-gray-100 flex items-center justify-center">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-image text-gray-400 text-5xl"></i>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-800 mb-3 text-lg">{{ $product->name }}</h3>
                            <p class="text-orange-500 font-bold text-xl mb-4">₱{{ number_format($product->price, 2) }}</p>
                            <div class="flex space-x-3">
                                <a href="{{ route('business.product.edit', $product->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600 transition-colors flex-1 text-center">Edit</a>
                                <button onclick="deleteProduct({{ $product->id }})" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-600 transition-colors flex-1">Delete</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-gray-400 text-7xl mb-6">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="text-2xl font-medium text-gray-900 mb-3">No products uploaded yet</h3>
                <p class="text-gray-500 mb-6 text-lg">Start by uploading your first product.</p>
                <a href="{{ route('business.product.create') }}" class="bg-blue-500 text-white px-8 py-4 rounded-lg hover:bg-blue-600 transition-colors duration-200 inline-flex items-center text-lg font-medium">
                    <i class="fas fa-plus mr-3"></i> Upload Product
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch(`/business/products/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}
</script>
@endsection