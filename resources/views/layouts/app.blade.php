<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title?? 'Laravel CRM' }}</title>
</head>
<body>
    <header>
        <h2>Laravel CRM</h2>
    </header>

    <main>
        {{$slot}}
    </main>

    
</body>
</html>