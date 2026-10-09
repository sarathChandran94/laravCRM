<!DOCTYPE html>
<html lang="en">
    <head @vite(['resources/css/app.css', 'resources/js/app.js'])>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{$title?? 'Laravel CRM' }}</title>    
</head>
<body>
    <header>
    <div class="navbar bg-base-100 shadow-sm mb-3">
        <div class="navbar-start">
            <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg aria-label="Menu" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /> </svg>
            </div>
                <ul
                    tabindex="-1"
                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                    
                    <li><a class="rounded text-blue-400" href="{{route('dashboard')}}"> Dashboard </a></li>
                    <li><a class="rounded text-blue-400" href="{{route('customers')}}"> Customers </a></li>
                    <li><a class="rounded text-blue-400" href="{{route('leads')}}"> Leads </a></li>
                    <li><a class="rounded text-blue-400" href="{{route('deals')}}"> Deals </a></li>
                </ul>
            </div>
            <a href="{{route('dashboard')}}" class="btn btn-ghost text-xl">Laravel CRM</a>
        </div>
        @if (auth()->user())
            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1">
                    <li><a class="rounded text-blue-400" href="{{route('dashboard')}}"> Dashboard </a></li>
                    <li><a class="rounded text-blue-400" href="{{route('customers')}}"> Customers </a></li>
                    <li><a class="rounded text-blue-400" href="{{route('leads')}}"> Leads </a></li>
                    <li><a class="rounded text-blue-400" href="{{route('deals')}}"> Deals </a></li>
                </ul>
            </div>
        @endif
        <div class="navbar-end px-2">
            @if (!auth()->user())
                <div class="px-2">
                    <a href="{{ route('login') }}" class="btn">Login</a>   
                </div>
                <div class="px-2">
                    <a href="{{ route('register') }}" class="btn">Register</a>
                </div>
            @else
                <div class="text-sm text-gray-600 px-2">
                    Welcome, <span class="font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                    >
                        Logout
                    </button>
                </form>
            @endif
        </div>
    </div>
    </header>

    <main>
        {{$slot}}
    </main>

    
</body>
</html>