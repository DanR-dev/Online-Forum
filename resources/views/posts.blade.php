<!DOCTYPE html>
<html>
@csrf
<x-contentLayout>
    <x-slot name="specificHeader">
        <form method="POST" action="/posts">
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
            readerName = "{{$user->profile->name}}";
            readerAuth = "{{$user->profile->auth}}";

            function placePostOptions(postId, authorId, title, content) {
                if(focusId != "optionspost"+postId){
                    if (focusId != "") {
                        document.getElementById(focusId).innerHTML = "";
                    }
                    comp = "<a onclick=placeCommentOnPostInput('"+postId+"')>Reply</a> ";
                    if(authorId == readerId){
                        comp += `<a onclick="placeEditPostInput('`+postId+`', '`+title+`', '`+content+`')">Edit</a> `;
                    }
                    if(authorId == readerId || readerAuth == 'admin'){
                        comp += genDeletePostButton(postId);
                    }
                    document.getElementById("optionspost"+postId).innerHTML = comp;
                    focusId = "optionspost"+postId;
                } else{
                    document.getElementById(focusId).innerHTML = "";
                    focusId = "";
                }
            }

            function placeCommentOptions(commentId, authorId, content) {
                if(focusId != "optionscomment"+commentId){
                    if (focusId != "") {
                        document.getElementById(focusId).innerHTML = "";
                    }
                    comp = "<a onclick=placeCommentOnCommentInput('"+commentId+"')>Reply</a> ";
                    if(authorId == readerId){
                        comp += `<a onclick="placeEditCommentInput('`+commentId+`', '`+content+`')">Edit</a> `;
                    }
                    if(authorId == readerId || readerAuth == 'admin'){
                        comp += genDeleteCommentButton(commentId);
                    }
                    document.getElementById("optionscomment"+commentId).innerHTML = comp;
                    focusId = "optionscomment"+commentId;
                } else{
                    document.getElementById(focusId).innerHTML = "";
                    focusId = "";
                }
            }

            function genDeletePostButton(postId){
                return ` <a onclick="deletePost('`+postId+`')">Delete</a>`;
            }

            function genDeleteCommentButton(commentId){
                return ` <a onclick="deleteComment('`+commentId+`')">Delete</a>`;
            }

            function placeCommentOnPostInput(postId){
                document.getElementById("optionspost"+postId).innerHTML = 
                `<form id=formcomment`+postId+` action='javascript:void(0);' onsubmit="createCommentOnPost('`+postId+`')">`
                +`<input type='text'></input>`
                +`<input type='submit' value='Reply'></input>`
                +`</form>`;
            }

            function placeCommentOnCommentInput(commentId){
                document.getElementById("optionscomment"+commentId).innerHTML = 
                `<form id=formcomment`+commentId+` action='javascript:void(0);'  onsubmit="createCommentOnComment('`+commentId+`')">`
                +`<input type='text'></input>`
                +`<input type='submit' value='Reply'></input>`
                +`</form>`;
            }

            function placeEditCommentInput(commentId, content){
                document.getElementById("optionscomment"+commentId).innerHTML = 
                `<form id=formcomment`+commentId+` action='javascript:void(0);' onsubmit="editComment('`+commentId+`')">`
                +"<input type='text' value='"+content+"'></input>"
                +"<input type='submit' value='Edit'></input>"
                +"</form>";
            }

            function placeEditPostInput(postId, title, content){
                document.getElementById("optionspost"+postId).innerHTML = 
                `<form id=formpost`+postId+` action='javascript:void(0);' onsubmit="editPost('`+postId+`')">`
                +"<input type='text' name=title value='"+title+"'></input>"
                +"<input type='text' name=content value='"+content+"'></input>"
                +"<input type='submit' value='Edit'></input>"
                +"</form>";
            }

            function editPost(postId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formpost"+postId));
                form.append("postId", postId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split(">");
                        document.getElementById("post"+postId).outerHTML = genPost(responses[0], readerId, readerName, responses[1], responses[2]);
                        document.getElementById(focusId).innerHTML = "";
                        focusId = "";
                    }
                };
                xhttp.open("POST", "posts/edit", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(form);
                return false;
            }

            function genPost(postId, authorId, authorName, title, content){
                return `<a id="post`+postId+`">`
                +`<div onclick="placePostOptions('`+postId+`', '`+authorId+`', '`+title+`', '`+content+`')">`
                +`<h3><i>`+authorName+`</i> : `+title+`</h3>`
                +content
                +`</div>`
                +`<p id="optionspost`+postId+`"></p>`
                +`</a>`;
            }

            function genComment(commentId, authorId, authorName, content){
                return `<a id=comment`+commentId+`>`
                +`<p>`
                +`<li onclick="placeCommentOptions('`+commentId+`', '`+authorId+`', '`+content+`')">`
                +`<i>`+authorName+`</i> : `+content
                +`</li>`
                +`</p>`
                +`<a id="optionscomment`+commentId+`"></a>`
                +`</a>`;
            }
        </script>

        <p>
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </p>
    
        @foreach ($posts as $post)
            @include('components/post', ['post' => $post])
        @endforeach

        <p>
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </p>
    </x-slot>
</x-contentLayout>

</html>
