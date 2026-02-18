@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-brand hover:text-indigo-700">← Back to Users</a>
</div>

<h1 class="text-2xl font-semibold mb-6">Edit User</h1>

<form action="{{ route('admin.users.update', $user) }}" method="POST" class="max-w-2xl">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-lg shadow p-6 space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-md">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-md">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input type="password" name="password"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
            <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-md">
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                   class="h-4 w-4 text-brand border-gray-300 rounded">
            <label class="ml-2 block text-sm text-gray-900">Active Account</label>
        </div>

        <div class="flex space-x-4 pt-4">
            <button type="submit" class="px-6 py-2 bg-brand hover:bg-brand-dark text-white font-medium rounded-md">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </div>
</form>
@endsection

