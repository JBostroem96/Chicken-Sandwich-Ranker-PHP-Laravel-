@extends('layouts.app')
@section('title', $page_title ?? 'Search')
@section('h1', $h1 ?? 'Search')

@section('content')

    <form method="GET" class="border-2 p-5 border-brandOrange rounded-lg" action="{{ route('chicken-sandwiches.index') }}">
        <div class="p-3 text-brandOrange font-bold">
            
            <label for="search-term" class="block mb-2 font-extrabold text-lg">
            
            </label>
            <input
                type="search"
                name="search-term"
                id="search-term"
                value="{{ request('search-term') }}"
                placeholder="e.g. Spicy Deluxe"
                class="block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-3 py-2 text-gray-800 placeholder-gray-400"
            >

            {{-- Search Type --}}
            <fieldset class="mb-3 mt-6">
                <legend class="font-extrabold mb-2 text-lg">{{ __('Search By:') }}</legend>

                <div class="flex items-center mb-3">
                    <input
                        type="radio"
                        name="search-type"
                        id="name"
                        value="name"
                        {{ request('search-type', 'name') === 'name' ? 'checked' : '' }}
                        class="mr-2 accent-orange-500 focus:ring-orange-500"
                    >
                    <label for="name" class="text-sm text-gray-800 font-semibold cursor-pointer select-none">
                        {{ __('Name') }}
                    </label>
                </div>

                <div class="flex items-center">
                    <input
                        type="radio"
                        name="search-type"
                        id="score"
                        value="score"
                        {{ request('search-type') === 'score' ? 'checked' : '' }}
                        class="mr-2 accent-orange-500 focus:ring-orange-500"
                    >
                    <label for="score" class="text-sm text-gray-800 font-semibold cursor-pointer select-none">
                        {{ __('Score') }}
                    </label>
                </div>
            </fieldset>
            
            <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:space-x-4">
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
