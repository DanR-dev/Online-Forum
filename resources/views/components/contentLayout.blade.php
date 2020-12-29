<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="/css/app.css" rel="stylesheet">
    <title>{{ $title ?? 'Content' }}</title>
</head>

<body>
    <header>
        <x-header>
            <x-slot name="loggedIn">
                {{ $loggedIn }}
            </x-slot>
        </x-header>
        {{$specificHeader}}
    </header>

    <div>
        <h1>{{ $title ?? 'Content' }}</h1>
    </div>
    <div>{{ $content }}</div>
</body>

</html>
