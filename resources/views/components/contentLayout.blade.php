<!DOCTYPE html>
<html>

<head>
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
