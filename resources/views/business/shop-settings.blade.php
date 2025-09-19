@extends('layouts.app')

@section('title', 'Shop Settings')

@section('content')
<h1 class="text-2xl font-bold mb-6">Shop Settings</h1>

<form method="POST" action="{{ route('business.shop-settings.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label>Name</label>
        <input type="text" name="name" value="{{ $business->name }}" class="w-full p-2 border rounded">
    </div>
    <div class="mb-4">
        <label>Description</label>
        <textarea name="description" class="w-full p-2 border rounded">{{ $business->description }}</textarea>
    </div>
    <div class="mb-4">
        <label>Cover Image</label>
        <input type="file" name="cover_image" class="w-full p-2 border rounded">
        @if($business->cover_image)
            <img src="{{ asset('storage/' . $business->cover_image) }}" alt="" class="mt-2 w-full h-32 object-cover">
        @endif
    </div>
    <div class="mb-4">
        <label>Address</label>
        <input type="text" name="address" value="{{ $business->address }}" class="w-full p-2 border rounded">
    </div>
    <div class="mb-4">
        <label>Phone</label>
        <input type="text" name="phone" value="{{ $business->phone }}" class="w-full p-2 border rounded">
    </div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
</form>
@endsection