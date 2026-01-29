{{-- resources/views/results.blade.php --}}
@extends('layouts.app')

@section('title', $page_title ?? 'Results')
@section('h1', $h1 ?? 'Results')
@section('content')

    {{-- Display success or error messages --}}

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
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


    {{---------------------------------------------------}}
                {{-- RESULTS LISTING --}}
    {{---------------------------------------------------}}

    @if ($all_chicken_sandwiches->isEmpty())
        <div class="border border-orange-500 border-dashed">
            <h2 class="text-red-600 font-bold text-2xl text-center">No Results found</h2>
        </div>
    @endif

    @php $rank = 0; @endphp
        
    @foreach ($all_chicken_sandwiches as $chicken_sandwich)
        @php
            $rank++;
            $found_rating = $ratings[$chicken_sandwich->id] ?? null;
        @endphp

        <p class="text-orange-600 font-bold text-3xl">{{ $rank }}.</>

        <div class="flex flex-col md:flex-row-reverse gap-4 mb-8">
            
            <div class="flex-1 border-4 border-orange-400 bg-orange-500 rounded-md text-white text-[30px] font-extrabold p-20 text-center">
                <p>RATED: <br>{{ round($chicken_sandwich->average_score, 1) }}/10</p>
                <p class="mt-4">NUMBER OF RATINGS: {{ $chicken_sandwich->entries }}</p>
            </div>

            <div class="flex-1 w-full border-4 border-orange-500 bg-white rounded-xl p-20">
                <table class="w-full">
                    <tr>
                        <td class="flex flex-col items-center gap-8 text-white py-4">
                            <img src="{{ asset('storage/' . $chicken_sandwich->logo) }}" alt="logo" class="w-[10rem] md:w-[15rem]">
                        </td>
                        <td class="flex flex-col items-center gap-8 text-white py-4">
                            <img src="{{ asset('storage/' . $chicken_sandwich->image) }}" alt="chicken sandwich" class="w-[15rem] md:w-[20rem]">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-center py-4">
                            <h2 class="text-orange-600 font-extrabold text-3xl">{{ $chicken_sandwich->name }}</h2>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="text-center py-4 text-orange-600 font-extrabold">
                            @auth
                            
                                @if (!$found_rating || $found_rating->chicken_sandwich_id !== $chicken_sandwich->id)
                                    <form action="{{ route('user-chicken-sandwiches.store') }}" method="POST" class="flex flex-col items-center gap-4">
                                        @csrf
                                        <input type="hidden" name="chicken_sandwich_id" value="{{ $chicken_sandwich->id }}">

                                        <div class="flex flex-col items-center">
                                            <label for="score" class="mt-5 font-extrabold text-orange-600">Score</label>
                                            <input type="number" name="score" 
                                                min="1" 
                                                max="10"
                                                class="mt-2 mb-3 w-24 text-center p-2 border border-orange-400 rounded-lg">
                                        </div>

                                        <div class="flex flex-col items-center">
                                            <label for="review" class="font-extrabold text-orange-600">Review</label>
                                            <textarea rows="2" cols="20" name="review"
                                                    class="mt-2 p-2 border border-orange-400 rounded-lg"></textarea>
                                        </div>

                                        <button type="submit"
                                                class="bg-orange-500 border-4 border-orange-400 text-white font-extrabold text-lg px-4 py-2 rounded-xl mt-2 hover:shadow-md hover:shadow-orange-500">
                                            RATE ME!
                                        </button>

                                        <p class="text-sm text-gray-500 font-semibold">Only 1-10 is allowed</p>
                                    </form>
                                @else
                                    <h2 class="text-green-600 font-bold text-xl">RATED</h2>
                                @endif

                                @role ('admin')
                                    <form action="{{ route('chicken-sandwiches.destroy', $chicken_sandwich->id) }}" method="POST" class="mt-4">
                                        @csrf
                                        
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-orange-500 border-4 border-orange-400 text-white font-extrabold text-lg px-4 py-2 rounded-xl mt-2 hover:shadow-md hover:shadow-red-500">
                                            DELETE
                                        </button>
                                    </form>

                                    <a href="{{ route('chicken-sandwiches.edit', $chicken_sandwich->id) }}"
                                    value="{{ $chicken_sandwich->id }}" name="chicken_sandwich_id" class="inline-block mt-2 bg-orange-500 border-4 border-orange-400 text-white font-extrabold text-lg px-4 py-2 rounded-xl hover:shadow-md hover:shadow-orange-500">
                                        EDIT
                                    </a>
                                @endrole
                            @else
                                <h2 class="text-black font-bold text-xl mt-3">Login to rate!</h2>
                            @endauth
                        </td>
                    </tr>

                    <tr>
                        <td class="text-center text-orange-600 font-bold pt-4">
                            Company: {{ $chicken_sandwich->company }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
@endsection
