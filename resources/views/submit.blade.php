@extends('layouts.app')
@section('title', $page_title ?? 'Submit')
@section('h1', $h1 ?? 'Submit')

 
@section('content')

    <!-- This page either inserts or updates a chicken sandwich depending on the action !-->
    @php
        $isUpdate = isset($chicken_sandwich->id) || isset($chicken_sandwich_to_update);
        $chicken_sandwich = $chicken_sandwich_to_update ?? $chicken_sandwich ?? null;
        $formAction = $isUpdate
            ? route('chicken-sandwiches.update', $chicken_sandwich->id)
            : route('chicken-sandwiches.store');

    @endphp

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

    <form enctype="multipart/form-data"
      id="submit-form"
      method="POST"
      action="{{ $formAction }}"
      class="needs-validation border-2 p-5 border-brandOrange rounded-lg"
      novalidate>
        @csrf
        @if($isUpdate)
            @method('PUT')
        @endif

        <h2 class="text-2xl font-extrabold mb-4">
            @if($isUpdate)
                Update Chicken Id {{ $chicken_sandwich->id ?? '' }}
            @else
                Enter a Chicken Sandwich
            @endif
        </h2>
        {{-- Name --}}
        <div class="mb-4">
            <label for="name" class="block mb-2 font-extrabold text-lg text-gray-800">Name</label>
            <input type="text" name="name" id="name" placeholder="Name" 
                value="{{ old('name', $chicken_sandwich ? $chicken_sandwich->getName() : '') }}"
                required
                class="block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-3 py-2 text-gray-800 placeholder-gray-400">
            <div class="mt-1 text-sm">Please provide a valid name.</div>
        </div>

        <div class="mb-4">
            <label for="company" class="block mb-2 font-extrabold text-lg text-gray-800">Company</label>
            <input type="text" name="company" id="company" placeholder="Company" 
                value="{{ old('company', $chicken_sandwich ? $chicken_sandwich->getCompany() : '') }}"
                required
                class="block w-full rounded-md border-gray-300 focus:border-orange-500 focus:ring-orange-500 px-3 py-2 text-gray-800 placeholder-gray-400">
            <div class="mt-1 text-sm">Please provide a company.</div>
        </div>
        {{-- WIP image and logo upload feature --}}
        <!-- 
        {{-- Image --}}
        <div class="mb-4">
            <label for="image" class="block mb-2 font-extrabold text-lg text-gray-800">Image</label>
            <input type="file" id="image" name="image" 
                class="block w-full text-gray-800" {{ $isUpdate ? '' : 'required' }}>
            <div class="mt-1 text-sm">Please provide a valid image.</div>
        </div>

        {{-- Logo --}}
        <div class="mb-4">
            <label for="logo" class="block mb-2 font-extrabold text-lg text-gray-800">Logo</label>
            <input type="file" id="logo" name="logo" 
                class="block w-full text-gray-800" {{ $isUpdate ? '' : 'required' }}>
            <div class="mt-1 text-sm">Please provide a valid logo.</div>
        </div>
        !-->
        {{-- Submit button --}}
        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:space-x-4">
            <button type="submit"
                name="{{ $isUpdate ? 'chicken-sandwich-update' : 'enter-chicken-sandwich' }}"
                value="{{ $chicken_sandwich->id ?? '' }}"
                class="bg-orange-500 hover:bg-orange-600 border-4 border-orange-300 rounded-xl px-6 py-2 font-bold text-white text-lg mb-3 sm:mb-0">
                Submit Chicken
            </button>
        </div>
    </form>

    {{-- Duplicate entry message --}}
    @if(session('duplicate_entry'))
        <p class="text-red-600 font-bold mt-4">That entry already exists</p>
    @endif

</main>

@endsection
