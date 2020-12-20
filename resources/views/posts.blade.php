<!DOCTYPE html>
<html>
<x-contentLayout>
    <x-slot name="title">
        Posts and comments and stuff
    </x-slot>
    <x-slot name="specificHeader">
        <form method="POST" action="/posts">
            @csrf
            <input id="searchName" type="Search" name="searchName" class="@error('title') is-invalid @enderror">
            <input type="Submit" name="search" value="Search" class="btn btn-dark btn-block">
        </form>
    </x-slot>
    <x-slot name="loggedIn">
        {{ $loggedIn }}
    </x-slot>
    <x-slot name="currentPage">
        posts
    </x-slot>
    <x-slot name="content">
        @foreach ($posts as $post)
            <div>
                <h3>{{ $post->title }}</h3>
                <i>{{ $post->profile->name }}</i> :
                <br>{{ $post->content }}

                @include('components/commentsOf', ['commentable' => $post])
            </div>
        @endforeach
    </x-slot>
</x-contentLayout>

</html>
