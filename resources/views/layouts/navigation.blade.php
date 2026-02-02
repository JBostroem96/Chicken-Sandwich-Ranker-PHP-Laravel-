<header x-data="{ open: false }">
    <nav class="bg-brandOrange text-white border-b-4 font-oswald">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="text-2xl font-extrabold">Chicken Sandwich Ranker</a>
                </div>

                <div class="flex md:hidden">
                    <button @click="open = !open" type="button" class="focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <div class="hidden md:flex md:items-center md:space-x-6">
                    <a href="{{ url('/') }}" class="hover:text-orange-400 font-bold">Home</a>
                    <a href="{{ url('/search') }}" class="hover:text-orange-400 font-bold">Chicken Sandwich</a>

                    @role ('admin')
                        <a href="{{ url('/submit') }}" class="hover:text-orange-400 font-bold">Enter Chicken Sandwich</a>
                    @endrole
                </div>

                <div class="hidden md:flex md:items-center md:space-x-4">
                    @auth
                        <a href="{{ url('/profile') }}">
                            
                                
                            <span class="bg-orange-500 text-white font-bold px-4 py-2 rounded-lg border-2 border-white hover:bg-white hover:text-orange-500 transition-colors">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-orange-500 text-white font-bold px-4 py-2 rounded-lg border-2 border-white hover:bg-white hover:text-orange-500 transition-colors">
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{ url('/sign-up') }}" class="hover:text-orange-400 font-bold">Sign Up</a>
                        <a href="{{ url('/login') }}" class="hover:text-orange-400 font-bold">Log In</a>
                    @endauth
                </div>

            </div>

            <div class="md:hidden" x-show="open" @click.away="open = false">
                <div class="px-2 pt-2 pb-3 space-y-2">
                    <a href="{{ url('/') }}" class="block px-3 py-2 rounded-lg border-2 border-white hover:bg-orange-500 hover:text-white font-bold">Home</a>
                    <a href="{{ url('/search') }}" class="block px-3 py-2 rounded-lg border-2 border-white hover:bg-orange-500 hover:text-white font-bold">Chicken Sandwich</a>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ url('/submit') }}" class="block px-3 py-2 rounded-lg border-2 border-white hover:bg-orange-500 hover:text-white font-bold">
                                Enter Chicken Sandwich
                            </a>
                        @endif
                        <a href="{{ url('/profile') }}">
                            <span class="w-full bg-orange-500 text-white font-bold px-4 py-2 rounded-lg border-2 border-white hover:bg-white hover:text-orange-500 transition-colors">{{ auth()->user()->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full bg-orange-500 text-white font-bold px-4 py-2 rounded-lg border-2 border-white hover:bg-white hover:text-orange-500 transition-colors">
                                Log Out
                            </button>
                        </form>
                    @else
                        <a href="{{ url('/sign-up') }}" class="block px-3 py-2 rounded-lg border-2 border-white hover:bg-orange-500 hover:text-white font-bold">Sign Up</a>
                        <a href="{{ url('/login') }}" class="block px-3 py-2 rounded-lg border-2 border-white hover:bg-orange-500 hover:text-white font-bold">Log In</a>
                    @endauth
                </div>
            </div>

        </div>
    </nav>
</header>
