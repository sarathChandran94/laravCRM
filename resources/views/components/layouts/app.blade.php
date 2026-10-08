<!DOCTYPE html>
<html lang="en">
    <head @vite(['resources/css/app.css', 'resources/js/app.js'])>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{$title?? 'Laravel CRM' }}</title>    
</head>
<body>
    <header>
        <h2>Laravel CRM</h2>
        <nav class="flex items-start gap-2 p-1">
            <a class="rounded text-blue-400" href="{{route('dashboard')}}"> Dashboard </a>
            <a class="rounded text-blue-400" href="{{route('customers')}}"> Customers </a>
            <a class="rounded text-blue-400" href="{{route('leads')}}"> Leads </a>
            <a class="rounded text-blue-400" href="{{route('deals')}}"> Deals </a>
        </nav>
    </header>

    <div class="navbar bg-base-100 shadow-sm">
        <div class="navbar-start">
            <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg aria-label="Menu" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /> </svg>
            </div>
            <ul
                tabindex="-1"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                <li><a>Item 1</a></li>
                <li>
                <a>Parent</a>
                <ul class="p-2">
                    <li><a>Submenu 1</a></li>
                    <li><a>Submenu 2</a></li>
                </ul>
                </li>
                <li><a>Item 3</a></li>
            </ul>
            </div>
            <a class="btn btn-ghost text-xl">Laravel CRM</a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
            <li><a>Item 1</a></li>
            <li>
                <details>
                <summary>Parent</summary>
                <ul class="p-2 bg-base-100 w-40 z-1">
                    <li><a>Submenu 1</a></li>
                    <li><a>Submenu 2</a></li>
                </ul>
                </details>
            </li>
            <li><a>Item 3</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            <a class="btn">Login</a>
        </div>
        </div>

    <main>
        {{$slot}}
    </main>

    
</body>
</html>