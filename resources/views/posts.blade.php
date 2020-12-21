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
        <script>
            focusId = "";

            function placeCommentableOptions(commentableId, authorId) {
                if(focusId != commentableId){
                    readerId = {{$user->profile->id}};
                    if (focusId != "") {
                        document.getElementById(focusId).innerHTML = "";
                    }
                    comp = "Reply";
                    if(authorId == readerId){
                        comp += "   Edit   Delete";
                    }
                    document.getElementById(commentableId).innerHTML = comp;
                    focusId = commentableId;
                } else{
                    document.getElementById(focusId).innerHTML = "";
                    focusId = "";
                }
            }

        </script>
        @foreach ($posts as $post)
            <div onclick="placeCommentableOptions('post{{ $post->id }}', '{{ $post->profile->id}}')">
                <h3><i>{{ $post->profile->name }}</i> : {{ $post->title }}</h3>
                {{ $post->content }}
                <p id="post{{ $post->id }}"></p>
            </div>

                @include('components/commentsOf', ['commentable' => $post])
        @endforeach
    </x-slot>
</x-contentLayout>


</html>
