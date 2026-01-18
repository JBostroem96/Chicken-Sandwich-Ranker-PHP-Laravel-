{{-- resources/views/profile.blade.php --}}
@extends('layouts.app')

@section('title', $page_title ?? 'Profile')
@section('h1', $h1 ?? 'Profile')
@section('content')

    @if (isset($message))
        <div class="{{ $message['type'] === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' }} border px-4 py-3 rounded mb-6">
            {{ $message['text'] }}
        </div>
    @endif

    <div class="flex flex-col gap-2 p-3 border-2 border-brandOrange text-brandOrange font-bold rounded-lg mb-6 bg-white shadow-md">
        <table class="w-full text-left table-auto mb-6">
            <tbody>
                <tr class="border-b">
                    <th class="py-2 px-4 font-semibold">Username:</th>
                    <td class="py-2 px-4">{{ auth()->user()->name }}</td>
                </tr>
                <tr class="border-b">
                    <th class="py-2 px-4 font-semibold">Date Created:</th>
                    <td class="py-2 px-4">{{ auth()->user()->created_at }}</td>
                </tr>
        
            </tbody>
        </table>

        <a href="{{ route('profile.change-password') }}" class="bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 rounded-xl px-4 py-1 font-bold text-white text-lg mb-3 sm:mb-0 text-center">
            CHANGE PASSWORD
        </a>
        <a href="{{ route('profile.ratings.index') }}" class="bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 rounded-xl px-4 py-1 font-bold text-white text-lg mb-3 sm:mb-0 text-center">
            VIEW RATINGS
        </a>
    </div>

</main>

@endsection
