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
        <nav>
            <a href="{{route('dashboard')}}"> Dashboard </a>
            <a href="{{route('customers')}}"> Customers </a>
            <a href="{{route('leads')}}"> Leads </a>
        </nav>
    </header>

    <main>
        {{$slot}}
    </main>

    
</body>
</html>