@extends('layouts.app')
@section('title', $page_title ?? 'Home')
@section('h1', $h1 ?? 'Welcome!')
@section('content')
    <p class="text-center mt-5">
        This is a Web Application that ranks submitted chicken sandwiches by their score. It has CRUD, and authentication and authorization.
        The submissions are done by the admin, then users can score them.
    </p>
@endsection
