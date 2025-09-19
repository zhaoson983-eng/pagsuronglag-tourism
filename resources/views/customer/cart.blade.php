@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 pt-20 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">My Cart</h1>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if($groupedCartItems->isEmpty())
                <div class="p-12 text-center">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-500 mb-6">Start shopping to add items to your cart.</p>
                    <a href="{{ route('customer.products') }}" class="bg-orange-500 text-white px-6 py-3 rounded-md hover:bg-orange-600 transition-colors duration-200 font-semibold">
                        Browse Products
                    </a>
                </div>
            @else
                @foreach($groupedCartItems as $businessId => $items)
                    @php
                        $business = $items->first()->product->business;
                        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);
                    @endphp

                    <div class="p-6 border-b border-gray-200 last:border-b-0 business-block" data-business-id="{{ $businessId }}">

                        <h2 class="text-xl font-semibold text-gray-800 mb-4">From: {{ $business->name }}</h2>

                        <!-- Cart Items -->
                        <div class="space-y-4 mb-6">
                            @foreach($items as $item)
                                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg cart-item-row" data-cart-id="{{ $item->id }}" data-price="{{ $item->product->price }}">

                                    <!-- Product Image -->
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                        @if($item->product->image)
                                            <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        @endif
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                        @if($item->selected_flavor)
                                            <p class="text-sm text-gray-500">Flavor: {{ $item->selected_flavor }}</p>
                                        @endif
                                        <p class="text-orange-500 font-bold">₱{{ number_format($item->product->price, 2) }}</p>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-3">
                                        <form action="{{ route('customer.cart.update', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                            <button type="submit" class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center" 
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                        </form>
                                        
                                        <span class="font-semibold text-lg min-w-[2rem] text-center">{{ $item->quantity }}</span>
                                        
                                        <form action="{{ route('customer.cart.update', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                            <button type="submit" class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Total Price -->
                                    <div class="text-right">
                                        <p class="font-bold text-gray-800 item-total" data-cart-id="{{ $item->id }}">₱{{ number_format($item->product->price * $item->quantity, 2) }}</p>
                                    </div>

                                    <!-- Remove Button -->
                                    <form action="{{ route('customer.cart.remove', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors duration-200" 
                                                onclick="return confirm('Remove this item from cart?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <!-- Cart Summary -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-gray-800">Total Items:</span>
                                <span class="font-bold text-gray-800 business-total-items" data-business-id="{{ $businessId }}">{{ $items->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-800">Total Amount:</span>
                                <span class="text-xl font-bold text-orange-500 business-total-amount" data-business-id="{{ $businessId }}">₱{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center">
                            <button onclick="clearCartForBusiness({{ $businessId }})" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition-colors duration-200 text-sm">
                                Clear {{ $business->name }} Items
                            </button>
                            
                            <button type="button" 
                                    onclick="openCheckoutModal({{ $businessId }}, '{{ addslashes($business->name) }}')" 
                                    class="bg-orange-500 text-white px-8 py-3 rounded-md hover:bg-orange-600 transition-colors duration-200 font-semibold">
                                Checkout
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div id="checkoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Complete Your Order</h3>
                <button onclick="closeCheckoutModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="businessName" class="mb-4 text-center">
                <p class="text-gray-600">Order from: <span id="modalBusinessName" class="font-semibold"></span></p>
            </div>

            <form id="checkoutForm" method="POST">
                @csrf
                <input type="hidden" name="business_id" id="modal-business-id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Time</label>
                        <input type="text" name="pickup_time" placeholder="e.g., Tomorrow 2:00 PM, or leave blank for ASAP" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                        <textarea name="notes" rows="3" placeholder="Any special instructions or requests" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-orange-500 text-white py-2 px-4 rounded-md hover:bg-orange-600 transition-colors duration-200">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCheckoutModal(businessId, businessName) {
    const modal = document.getElementById('checkoutModal');
    const businessIdInput = document.getElementById('modal-business-id');
    const checkoutForm = document.getElementById('checkoutForm');
    const modalBusinessName = document.getElementById('modalBusinessName');
    
    if (!modal || !businessIdInput || !checkoutForm) {
        alert('Error: Modal elements not found. Please refresh the page.');
        return;
    }
    
    businessIdInput.value = businessId;
    checkoutForm.action = `/cart/checkout/${businessId}`;
    
    if (modalBusinessName) modalBusinessName.textContent = businessName;
    modal.classList.remove('hidden');
}

function closeCheckoutModal() {
    document.getElementById('checkoutModal').classList.add('hidden');
}
</script>
@endsection