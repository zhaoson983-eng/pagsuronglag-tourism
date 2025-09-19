@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Search results for: <span class="text-blue-600">{{ $query ?? $q ?? '' }}</span></h1>

    @if(($products->count() ?? 0) === 0 && ($businesses->count() ?? 0) === 0 && ($attractions->count() ?? 0) === 0)
        <div class="bg-white border rounded-lg p-8 text-center text-gray-600">
            No results found. Try a different keyword.
        </div>
    @endif

    @if(($products->count() ?? 0) > 0)
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Products</h2>
                <a href="{{ route('customer.products') }}" class="text-blue-600 hover:text-blue-800 text-sm">View all products</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition p-4">
                        <div class="h-40 w-full bg-gray-100 rounded mb-3 overflow-hidden flex items-center justify-center">
                            @if(!empty($product->image))
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                            @else
                                <i class="fas fa-image text-gray-400 text-3xl"></i>
                            @endif
                        </div>
                        <div class="mb-1 text-sm text-gray-500">{{ $product->business->name ?? '—' }}</div>
                        <div class="font-semibold text-gray-800">{{ $product->name }}</div>
                        <div class="text-orange-600 font-bold">₱{{ number_format($product->price, 2) }}</div>
                        <div class="mt-3">
                            <a href="{{ route('customer.product.show', $product) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">View</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(($businesses->count() ?? 0) > 0)
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Businesses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($businesses as $biz)
                    <a href="{{ route('customer.business.show', $biz) }}" class="bg-white rounded-lg shadow-sm border hover:shadow-md transition p-4 block">
                        <div class="h-40 w-full bg-gray-100 rounded mb-3 overflow-hidden flex items-center justify-center">
                            @if(!empty($biz->cover_image))
                                <img src="{{ asset('storage/' . $biz->cover_image) }}" alt="{{ $biz->name }}" class="h-full w-full object-cover">
                            @else
                                <i class="fas fa-store text-gray-400 text-3xl"></i>
                            @endif
                        </div>
                        <div class="font-semibold text-gray-800">{{ $biz->name }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($biz->description, 80) }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if(($attractions->count() ?? 0) > 0)
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Tourist Spots</h2>
                <a href="{{ route('customer.attractions') }}" class="text-blue-600 hover:text-blue-800 text-sm">View all spots</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($attractions as $spot)
                    <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition p-4">
                        <div class="h-40 w-full bg-gray-100 rounded mb-3 overflow-hidden flex items-center justify-center">
                            @if(!empty($spot->cover_photo))
                                <img src="{{ Storage::url($spot->cover_photo) }}" alt="{{ $spot->name }}" class="h-full w-full object-cover">
                            @else
                                <i class="fas fa-map-marked-alt text-gray-400 text-3xl"></i>
                            @endif
                        </div>
                        <div class="font-semibold text-gray-800">{{ $spot->name }}</div>
                        <div class="text-sm text-gray-500">{{ $spot->location }}</div>
                        <div class="text-sm text-gray-600 mt-1">{{ Str::limit($spot->short_info, 90) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
