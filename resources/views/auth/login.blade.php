@extends('layouts.app')
@section('title', $page_title ?? 'Login')
@section('h1', $h1 ?? 'Login')
@section('content')

    {{-- Session Status Message --}}
    @if (session('status'))
        <div class="mb-4 text-orange-500 font-bold text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" 
          class="border-2 border-brandOrange text-brandOrange font-bold p-6 rounded-lg">
        @csrf

        <div class="mb-4">
            <label for="email" class="text-black-500 font-bold text-lg">Email:</label>
            <input id="email" 
                   class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                   type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        
        <div class="mb-4">
            <label for="password" class="text-black-500 font-bold text-lg">Password:</label>
            <input id="password"
                   class="mt-1 block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                   type="password" name="password" required autocomplete="current-password" />
            @error('password')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        
        <div class="flex items-center mt-4">
            <input id="remember_me" type="checkbox"
                   class="rounded border-orange-300 text-orange-600 shadow-sm focus:ring-orange-500"
                   name="remember">
            <label for="remember_me" class="ml-2 text-sm text-orange-500 font-bold cursor-pointer">
                {{ __('Remember me') }}
            </label>
        </div>

        
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-blue-500 text-lg hover:text-orange-700 font-bold"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button 
                type="submit"
                class="ml-3 bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 text-white text-lg font-bold py-2 px-4 rounded-xl">
                {{ __('Log in') }}
            </button>
        </div>

        <p class="mt-6 text-lg font-bold">
            Don't have an account? 
            <a href="{{ route('register') }}" class="underline text-blue-500 hover:text-orange-700">
                Sign up here!
            </a>
        </p>
    </form>

@endsection
