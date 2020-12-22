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

            function placePostOptions(postId, authorId, title, content) {
                if(focusId != postId){
                    if (focusId != "") {
                        document.getElementById(focusId).innerHTML = "";
                    }
                    comp = "<a onclick=placeCommentInput('"+postId+"')>Reply</a> ";
                    if(authorId == readerId){
                        comp += `<a onclick="placeEditPostInput('`+postId+`', '`+title+`', '`+content+`')">Edit</a> `;
                    }
                    if(authorId == readerId || readerAuth == 'admin'){
                        comp += " Delete ";
                    }
                    document.getElementById(postId).innerHTML = comp;
                    focusId = postId;
                } else{
                    document.getElementById(focusId).innerHTML = "";
                    focusId = "";
                }
            }

            function placeCommentOptions(commentId, authorId, content) {
                if(focusId != commentId){
                    if (focusId != "") {
                        document.getElementById(focusId).innerHTML = "";
                    }
                    comp = "<a onclick=placeCommentInput('"+commentId+"')>Reply</a> ";
                    if(authorId == readerId){
                        comp += `<a onclick="placeEditCommentInput('`+commentId+`', '`+content+`')">Edit</a> `;
                    }
                    if(authorId == readerId || readerAuth == 'admin'){
                        comp += gendeleteButton(commentableId);
                    }
                    document.getElementById(commentId).innerHTML = comp;
                    focusId = commentId;
                } else{
                    document.getElementById(focusId).innerHTML = "";
                    focusId = "";
                }
            }

            function placeCommentInput(commentableId){
                document.getElementById(commentableId).innerHTML = 
                "<input type='text'></input>"
                +"<input type='submit' value='Reply'></input>";
            }

            function genDeleteButton(commentableId){
                return "<input type='submit' value='Delete'></input>";
            }

            function placeEditCommentInput(commentableId, content){
                document.getElementById(commentableId).innerHTML = 
                "<input type='text' value='"+content+"'></input>"
                +"<input type='submit' value='Edit'></input>";
            }

            function placeEditPostInput(commentableId, title, content){
                document.getElementById(commentableId).innerHTML = 
                "<input type='text' value='"+title+"'></input>"
                +"<input type='text' value='"+content+"'></input>"
                +"<input type='submit' value='Edit'></input>";
            }

        </script>

        <p>
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </p>
    
        @foreach ($posts as $post)
            <div onclick="placePostOptions('post{{ $post->id }}', '{{ $post->profile->id}}', '{{ $post->title}}', '{{ $post->content}}')">
                <h3><i>{{ $post->profile->name }}</i> : {{ $post->title }}</h3>
                {{ $post->content }}
            </div>
                <p id="post{{ $post->id }}"></p>

                @include('components/commentsOf', ['commentable' => $post])
        @endforeach

        <p>
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </p>
    </x-slot>
</x-contentLayout>

</html>
