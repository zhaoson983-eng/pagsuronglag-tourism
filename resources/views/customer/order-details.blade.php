@extends('layouts.app')

@section('title', 'Order Details - Pagsurong Lagonoy')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 text-white px-6 py-4">
                <h1 class="text-2xl font-serif font-bold">Order Details</h1>
                <p class="text-blue-100">Complete your reservation for pickup</p>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="business_id" value="{{ $product->business->id }}">

                    <!-- Product Information -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Product Information</h2>
                        <div class="flex items-center space-x-4">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-20 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-800">{{ $product->name }}</h3>
                                <p class="text-gray-600">{{ $product->description }}</p>
                                <p class="text-2xl font-bold text-blue-600 mt-2">₱{{ number_format($product->price, 2) }}</p>
                            </div>
                        </div>
                        
                        <!-- Business Information -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h4 class="font-medium text-gray-800 mb-2">Business: {{ $product->business->business_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $product->business->address }}</p>
                            <p class="text-sm text-gray-600">{{ $product->business->phone_number }}</p>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Details</h2>
                        
                        <!-- Quantity -->
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   min="1" 
                                   max="{{ $product->stock ?? 999 }}" 
                                   value="1"
                                   class="w-32 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Available stock: {{ $product->stock ?? 'Unlimited' }}</p>
                        </div>

                        <!-- Pickup Date -->
                        <div class="mb-4">
                            <label for="pickup_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Pickup Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="pickup_date" 
                                   name="pickup_date" 
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                        </div>

                        <!-- Pickup Time -->
                        <div class="mb-4">
                            <label for="pickup_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Pickup Time <span class="text-red-500">*</span>
                            </label>
                            <select id="pickup_time" 
                                    name="pickup_time" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                <option value="">Select pickup time</option>
                                <option value="08:00">8:00 AM</option>
                                <option value="09:00">9:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="15:00">3:00 PM</option>
                                <option value="16:00">4:00 PM</option>
                                <option value="17:00">5:00 PM</option>
                                <option value="18:00">6:00 PM</option>
                                <option value="19:00">7:00 PM</option>
                                <option value="20:00">8:00 PM</option>
                            </select>
                        </div>

                        <!-- Special Instructions -->
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Instructions (Optional)
                            </label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Any special requests or instructions for your order"></textarea>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h2>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Product Price:</span>
                                <span class="font-medium">₱{{ number_format($product->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Quantity:</span>
                                <span class="font-medium" id="summaryQuantity">1</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pickup Date:</span>
                                <span class="font-medium" id="summaryDate">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pickup Time:</span>
                                <span class="font-medium" id="summaryTime">-</span>
                            </div>
                            <hr class="my-2">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Amount:</span>
                                <span class="text-blue-600" id="summaryTotal">₱{{ number_format($product->price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="font-medium text-yellow-800 mb-2">
                            <i class="fas fa-info-circle mr-2"></i>Important Information
                        </h3>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            <li>• This is a reservation system - no payment is processed online</li>
                            <li>• Payment will be made when you pick up your order</li>
                            <li>• Please arrive on time for pickup</li>
                            <li>• Bring a valid ID for verification</li>
                            <li>• Contact the business if you need to reschedule</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 pt-6">
                        <a href="{{ route('customer.products') }}" 
                           class="flex-1 bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-400 text-center transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-shopping-cart mr-2"></i>Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const pickupDateInput = document.getElementById('pickup_date');
    const pickupTimeInput = document.getElementById('pickup_time');
    const productPrice = {{ $product->price }};
    
    function updateSummary() {
        const quantity = parseInt(quantityInput.value) || 1;
        const total = quantity * productPrice;
        
        document.getElementById('summaryQuantity').textContent = quantity;
        document.getElementById('summaryTotal').textContent = '₱' + total.toFixed(2);
    }
    
    function updateDateSummary() {
        const date = pickupDateInput.value;
        if (date) {
            const formattedDate = new Date(date).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('summaryDate').textContent = formattedDate;
        }
    }
    
    function updateTimeSummary() {
        const time = pickupTimeInput.value;
        if (time) {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            const displayTime = `${displayHour}:${minutes} ${ampm}`;
            document.getElementById('summaryTime').textContent = displayTime;
        }
    }
    
    quantityInput.addEventListener('input', updateSummary);
    pickupDateInput.addEventListener('change', updateDateSummary);
    pickupTimeInput.addEventListener('change', updateTimeSummary);
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    pickupDateInput.min = today;
});
</script>
@endsection

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap');
    
    .font-serif {
        font-family: 'Playfair Display', serif;
    }
</style>
@endsection
