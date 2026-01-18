@extends('layouts.app')
@section('title', $page_title ?? 'Register')
@section('h1', $h1 ?? 'Register')
@section('content')

    <form method="POST" action="{{ route('register') }}" 
        class="border-2 border-brandOrange text-brandOrange font-bold p-10 rounded-lg">
        @csrf

        <div class="mb-4">
            <label for="name" class="text-black-500 font-bold text-lg">Name:</label>
            <input id="name" 
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" 
                type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            @error('name')
                <p class="mt-2 text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="text-black-500 font-bold text-lg">Email:</label>
            <input id="email" 
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500" 
                type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            @error('email')
                <p class="mt-2 text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="text-black-500 font-bold text-lg">Password:</label>
            <input id="password" 
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                type="password" name="password" required autocomplete="new-password" />
            @error('password')
                <p class="mt-2 text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="text-black-500 font-bold text-lg">Confirm Password:</label>
            <input id="password_confirmation"
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            @error('password_confirmation')
                <p class="mt-2 text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col justify-between gap-5">

            <button type="submit"
                class="ml-3 bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 text-white text-lg font-bold py-2 px-4 rounded-xl">
                Register
            </button>
            <a class="underline text-blue-500 text-lg hover:text-orange-700 font-bold" href="{{ route('login') }}">
                Already registered? Log in
            </a>

        </div>
    </form>

@endsection
