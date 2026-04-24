@extends('layouts.app')

@section('title', $page_title ?? 'Edit Rating')
@section('h1', $h1 ?? 'Edit Rating')

@section('content')

    @if (session('success'))
        <div class="mb-4 text-green-600 font-bold text-center">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="mb-4 text-red-600 font-bold text-center">
            {{ session('error') }}
        </div>
    @elseif ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <ul>{{ $error }}</ul>
                @endforeach
            </ul>
        </div>
    @endif
                       
    <form action="{{ route('profile.ratings.update', $rating->chicken_sandwich_id) }}" class="border-2 p-5 border-brandOrange rounded-lg" method='POST'>
        @csrf
        @method('PUT')
        <p class="text-center text-orange-600 font-bold">Score:</p>
        <input type='number' 
                min="1" 
                max="10" 
                name='new_score' pattern='[1-9]|10' class="mt-2 p-2 border border-orange-400 rounded-lg w-full">
        
        <div class="flex flex-col items-center mt-5">
            <label for="review" class="font-extrabold text-orange-600">Review</label>
            <textarea rows="6" cols="40" name="review"
                class="mt-2 p-2 border border-orange-400 rounded-lg">{{ old('review', $rating->review) }}</textarea>
        </div>
        <button class="bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 rounded-xl px-6 py-2 font-bold text-white text-lg" type='submit' id='edit-score'
            name='edit-score'  value='<?=$rating->chicken_sandwich_id?>'>RATE ME!</button><p>Only 1-10 is allowed, one rating per account</p>
    </form>
@endsection                  
   