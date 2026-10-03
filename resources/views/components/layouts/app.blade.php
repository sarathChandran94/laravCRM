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

    <main>
        {{$slot}}
    </main>

    
</body>
</html>