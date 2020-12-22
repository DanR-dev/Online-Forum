<!DOCTYPE html>
<html>
<x-contentLayout>
    <x-slot name="specificHeader">
        <form method="POST" action="/posts">
            @csrf
            <p>
                <input id="searchName" type="Search" name="searchName" class="@error('title') is-invalid @enderror">
                <input type="Submit" name="search" value="Search by author" class="btn btn-dark btn-block">
            </p>
        </form>
    </x-slot>
    <x-slot name="title">
        Posts and comments and stuff
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
            readerId = {{$user->profile->id}};
            readerAuth = "{{$user->profile->auth}}";

            function placeCommentableOptions(commentableId, authorId) {
                if(focusId != commentableId){
                    if (focusId != "") {
                        document.getElementById(focusId).innerHTML = "";
                    }
                    comp = "Reply ";
                    if(authorId == readerId){
                        comp += " Edit ";
                    }
                    if(authorId == readerId || readerAuth == 'admin'){
                        comp += " Delete ";
                    }
                    document.getElementById(commentableId).innerHTML = comp;
                    focusId = commentableId;
                } else{
                    document.getElementById(focusId).innerHTML = "";
                    focusId = "";
                }
            }

        </script>

        <p>
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </p>
    
        @foreach ($posts as $post)
            <div onclick="placeCommentableOptions('post{{ $post->id }}', '{{ $post->profile->id}}')">
                <h3><i>{{ $post->profile->name }}</i> : {{ $post->title }}</h3>
                {{ $post->content }}
                <p id="post{{ $post->id }}"></p>
            </div>

                @include('components/commentsOf', ['commentable' => $post])
        @endforeach

        <p>
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </p>
    </x-slot>
</x-contentLayout>

</html>
