@extends('layouts.app')

@section('title', 'Archived Users')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mt-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Archived Users</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Back to Users
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-archive text-2xl text-gray-400"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">Total Archived Users</h3>
                <p class="text-2xl font-bold text-red-600">{{ $users->total() }}</p>
            </div>
        </div>
    </div>

    @if($users->count() > 0)
        <!-- Table -->
        <div class="bg-white shadow overflow-hidden rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Archived Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($user->profile && $user->profile->profile_picture)
                                        <img src="{{ Storage::url($user->profile->profile_picture) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full mr-3 opacity-50">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-sm font-semibold mr-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="font-medium text-gray-600">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($user->role === 'admin') bg-purple-100 text-purple-600
                                    @elseif($user->role === 'business_owner') bg-blue-100 text-blue-600
                                    @else bg-green-100 text-green-600
                                    @endif opacity-75">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->deleted_at ? $user->deleted_at->format('M d, Y H:i') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Restore this user?')">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <p class="text-sm text-gray-700">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results</p>
            {{ $users->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-archive text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Archived Users</h3>
            <p class="text-gray-500">There are currently no archived users in the system.</p>
            <a href="{{ route('admin.users') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <i class="fas fa-users mr-2"></i>
                View All Users
            </a>
        </div>
    @endif
</div>
@endsection
