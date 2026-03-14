@extends('layouts.app')

@section('title', $page_title ?? 'Verify Email')
@section('h1', $h1 ?? 'Verify Email')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg border-2 border-brandOrange">
    <div class="mb-4 text-md text-black">
        {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you. If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <!-- Resend Verification Email Form -->
        <form method="POST" class=" text-brandOrange font-bold p-6 rounded-lg" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
               class="ml-3 bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 text-white text-lg font-bold py-2 px-4 rounded-xl">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <!-- Logout Form -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="underline text-blue-500 text-lg hover:text-orange-700 font-bold">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>
@endsection
