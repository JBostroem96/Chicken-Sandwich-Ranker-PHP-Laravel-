@extends('layouts.app')

@section('title', $page_title ?? 'Change Password')
@section('h1', $h1 ?? 'Change Password')
@section('content')

@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<form action="{{ route('profile.password.update') }}" method="POST"
      class="border-2 border-brandOrange text-brandOrange font-bold p-8 rounded-lg flex flex-col gap-6 max-w-lg mx-auto">
    @csrf

    <div class="flex flex-col">
        <label for="current-password" class="font-bold text-lg">Current Password:</label>
        <input type="password" name="current_password" id="current-password"
               placeholder="Enter current password"
               class="mt-2 p-2 border border-orange-400 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
               required>
        @error('current_password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col">
        <label for="new-password" class="font-bold text-lg">New Password:</label>
        <input type="password" name="new_password" id="new-password"
               placeholder="Enter new password"
               class="mt-2 p-2 border border-orange-400 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
               required>
        @error('new_password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex flex-col">
        <label for="repeated-password" class="font-bold text-lg">Confirm Password:</label>
        <input type="password" name="new_password_confirmation" id="repeated-password"
               placeholder="Re-enter new password"
               class="mt-2 p-2 border border-orange-400 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
               required>
        @error('new_password_confirmation')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
            class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-xl border-4 border-orange-400 mt-4">
        Update Password
    </button>
</form>

@endsection
