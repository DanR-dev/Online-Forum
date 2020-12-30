<!DOCTYPE html>
@extends('app')
<html>
<head>
    <title>{{ $title ?? 'Content' }}</title>
</head>

<body>
    <script src="{{ asset('js/app.js')}}"></script>
    <header>
        <x-header>
            <x-slot name="loggedIn">
                {{ $loggedIn }}
            </x-slot>
        </x-header>
        {{$subHeader ?? ""}}
    </header>

    <div>
        <h1>{{ $title ?? 'Content' }}</h1>
    </div>
    <div>{{ $content }}</div>
</body>

</html>
