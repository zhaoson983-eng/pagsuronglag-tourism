@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<h1 class="text-2xl font-bold mb-6">Add New Product</h1>

<form method="POST" action="{{ route('business.product.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label>Name</label>
        <input type="text" name="name" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label>Price</label>
        <input type="number" step="0.01" name="price" class="w-full p-2 border rounded" required>
    </div>
    <div class="mb-4">
        <label>Description</label>
        <textarea name="description" class="w-full p-2 border rounded"></textarea>
    </div>
    <div class="mb-4">
        <label>Flavors</label>
        <input type="text" name="flavors" class="w-full p-2 border rounded" placeholder="e.g. Original, Spicy">
    </div>
    <div class="mb-4">
        <label>Image</label>
        <input type="file" name="image" class="w-full p-2 border rounded" required>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Product</button>
</form>
@endsection