@extends('layouts.app')

@section('title', $page_title ?? 'Ratings')
@section('h1', $h1 ?? 'Your Ratings')

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

    <table class="text-white text-bold border-4 border-orange-300 bg-brandOrange border-rounded">
        <tbody class="border border-dashed">
            @foreach ($ratings as $rating)
            {{-- EDIT SCORE FORM (only when editing this entry) --}}
                                
                @isset($id)

                    @if ((int)$id === $rating->id)

                        <form action="{{ route('profile.ratings.update', $rating->id) }}" method='POST'>
                            @csrf
                            @method('PUT')
                            <input type='numeric' name='new_score' pattern='[1-9]|10' class="rounded-md border-gray-500 focus:border-orange-500 focus:ring-orange-500 px-3 py-2 text-gray-800 placeholder-gray-400">
                            <div class="flex flex-col items-center">
                                <label for="review" class="font-extrabold text-orange-600">Review</label>
                                <textarea rows="2" cols="20" name="review"
                                    class="mt-2 p-2 border border-orange-400 rounded-lg">{{ old('review', $rating->pivot->review) }}</textarea>
                            </div>
                            <button class="bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 rounded-xl px-6 py-2 font-bold text-white text-lg mb-3 sm:mb-0" type='submit' id='edit-score'
                            name='edit-score'  value='<?=$id?>'>RATE ME!</button><p>Only 1-10 is allowed, one rating per account</p>
                        </form>
                    @endif
                        
                @else
                    <tr class="">
                        <th class="fw-bold"><span>Score:</span></th>
                        <td class="p-5"><span>{{ $rating->pivot->score}}</span></td>
                        <th class="fw-bold"><span>Name:</span></th>
                        <td class="p-5"><span>{{ $rating->name }}</span></td>
                        <form action="{{ route('profile.ratings.destroy', $rating->id) }}" 
                            method="POST" class="flex flex-row mt-6">
                            @csrf
                            @method('DELETE')
                            <td class="p-5">
                                <button type="submit"
                                    class="p-2 bg-brandOrange hover:bg-orange-600 border-4 border-orange-300 text-white font-bold rounded-xl">
                                    DELETE
                                </button>
                            </td>
                            <td class="p-5">{{-- EDIT BUTTON --}}
                                <a href="{{ route('profile.ratings.edit', $rating->id) }}"
                                    name="rating" class="p-2 bg-brandOrange hover:bg-orange-600 border-4 border-orange-300 text-white font-bold rounded-xl">
                                    EDIT
                                </a>
                            </td>
                        </form>
                    </tr>    
                @endisset
            @endforeach 
        </tbody>              
    </table>                      
@endsection
                
