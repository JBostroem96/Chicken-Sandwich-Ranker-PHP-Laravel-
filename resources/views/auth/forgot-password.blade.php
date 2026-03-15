@extends('layouts.app')

@section('title', $page_title ?? 'Forgot Password')
@section('h1', $h1 ?? 'Forgot Password')

@section('content')
    <div class="rounded-lg border-2 border-brandOrange p-5">
        <div class="mb-4 text-md">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>
    
        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4">
                <ul class="mt-1 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium">
                    {{ __('Email') }}
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 bg-white focus:outline-none sm:text-sm"
                />
            </div>

            <div class="flex items-center justify-center mt-4">
                <button
                    type="submit"
                    class="ml-3 bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 text-white text-lg font-bold py-2 px-4 rounded-xl"
                >
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
@endsection
