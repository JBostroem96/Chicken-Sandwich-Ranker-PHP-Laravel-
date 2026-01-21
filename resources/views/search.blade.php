@extends('layouts.app')
@section('title', $page_title ?? 'Search')
@section('h1', $h1 ?? 'Search')

@section('content')
    
    <form method="GET" class="border-2 p-5 border-brandOrange rounded-lg" action="{{ route('chicken-sandwiches.index') }}">
        <div class="p-3 text-brandOrange font-bold" x-data="{ type: 'name' }">
            
            <label>Name</label>
                <input
                    type="radio"
                    name="search-type"
                    id="search-type"
                    value="name"
                    x-model="type"
                   
                >
            
            <br>
            <label>Score</label>
              <input type="radio"
                    name="search-type"
                    id="search-type"
                    value="score"
                    x-model="type"
                    
                >
            
            <div x-show="type === 'name'">
                <input type="text" name="name" placeholder="Search by name">
            </div>

            <div x-show="type === 'score'">
                <input type="number" name="min_score" step="0.1" placeholder="Search by mininmum score">
            </div>
            
            <div x-show="type === 'score'">
                <input type="number" name="max_score" step="0.1" placeholder="Search by maximum score">
            </div>
        
            <div class="mt-6">
                <button
                    type="submit"
                    name="search"
                    class="bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 rounded-xl px-6 py-2 font-bold text-white text-lg mb-3 sm:mb-0"
                >
                    {{ __('Search') }}
                </button>

                <button
                    type="submit"
                    name="view-all"
                    class="bg-gray-600 hover:bg-gray-700 border-4 border-orange-300 rounded-xl px-6 py-2 font-bold text-white text-lg"
                >
                    {{ __('Search All') }}
                </button>
            </div>
        </div>
    </form>
@endsection
