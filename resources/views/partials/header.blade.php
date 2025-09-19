<header class="bg-white shadow-sm border-b">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold text-blue-600">Pagsurong Lagonoy</a>

        @auth
            <nav class="flex space-x-4">
                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('customer.products') }}" class="hover:text-blue-600">Products</a>
                    <a href="{{ route('customer.orders') }}" class="hover:text-blue-600">My Orders</a>
                    <a href="{{ route('customer.messages') }}" class="hover:text-blue-600">Messages</a>
                @elseif(auth()->user()->is_business_owner)
                    <a href="{{ route('business.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('business.products') }}" class="hover:text-blue-600">My Products</a>
                    <a href="{{ route('business.orders') }}" class="hover:text-blue-600">Orders</a>
                    <a href="{{ route('business.messages') }}" class="hover:text-blue-600">Messages</a>
                    <a href="{{ route('business.shop-settings') }}" class="hover:text-blue-600">Shop Settings</a>
                @elseif(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Admin Dashboard</a>
                    <a href="{{ route('admin.analytics') }}" class="hover:text-blue-600">Analytics</a>
                    <a href="{{ route('admin.users') }}" class="hover:text-blue-600">Users</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-red-600">Logout</button>
                </form>
            </nav>
        @else
            <nav class="flex space-x-4">
                <a href="{{ route('login') }}" class="hover:text-blue-600">Login</a>
                <a href="{{ route('register') }}" class="hover:text-blue-600">Register</a>
            </nav>
        @endauth
    </div>
</header>