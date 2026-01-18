<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.head')
    </head>
    <body class="flex flex-col justify-content-center">
        <header>
                @include('layouts.navigation')
        </header>

        <div class="mx-auto">
            
            <main class="p-5">
                
                <h1 class="text-center text-4xl font-bold mb-4 mt-5">@yield('h1', '')</h1>

                @yield('content')
            </main>
        </div>
    </body>
</html>
