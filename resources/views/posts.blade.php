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
                `<form id=formpost`+postId+` action='javascript:void(0);' onsubmit="createCommentOnPost('`+postId+`')">`
                +`<input type='text' name='content'></input>`
                +`<input type='submit' value='Reply'></input>`
                +`</form>`;
            }

            function placeCommentOnCommentInput(commentId){
                document.getElementById("optionscomment"+commentId).innerHTML = 
                `<form id=formcomment`+commentId+` action='javascript:void(0);'  onsubmit="createCommentOnComment('`+commentId+`')">`
                +`<input type='text' name='content'></input>`
                +`<input type='submit' value='Reply'></input>`
                +`</form>`;
            }

            function placeEditCommentInput(commentId, content){
                document.getElementById("optionscomment"+commentId).innerHTML = 
                `<form id=formcomment`+commentId+` action='javascript:void(0);' onsubmit="editComment('`+commentId+`')">`
                +"<input type='text' name='content' value='"+content+"'></input>"
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

            function editComment(commentId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formcomment"+commentId));
                form.append("commentId", commentId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split(">");
                        document.getElementById("comment"+commentId).outerHTML = genComment(responses[0], readerId, readerName, responses[1]);
                        document.getElementById(focusId).innerHTML = "";
                        focusId = "";
                    }
                };
                xhttp.open("POST", "comments/edit", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(form);
                return false;
            }

            function deletePost(postId){
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if(this.responseText == 'True'){
                            document.getElementById("post"+postId).outerHTML = null;
                            document.getElementById("commentsonpost"+postId).outerHTML = null;
                        } else{
                            document.write("delete error occured");
                            document.getElementById(focusId).innerHTML = "";
                        }
                        focusId = "";
                    }
                };
                xhttp.open("POST", "posts/delete", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(postId);
                return false;
            }

            function deleteComment(commentId){
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if(this.responseText == 'True'){
                            document.getElementById("comment"+commentId).outerHTML = null;
                            document.getElementById("commentsoncomment"+commentId).outerHTML = null;
                        } else{
                            document.write("delete error occured");
                            document.getElementById(focusId).innerHTML = "";
                        }
                        focusId = "";
                    }
                };
                xhttp.open("POST", "comments/delete", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(commentId);
                return false;
            }

            function createCommentOnPost(postId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formpost"+postId));
                form.append("postId", postId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split(">");
                        document.getElementById("commentsonpost"+postId).innerHTML = genComment(responses[0], readerId, readerName, responses[1])
                        + document.getElementById("commentsonpost"+postId).innerHTML;
                        document.getElementById(focusId).innerHTML = "";
                        focusId = "";
                    }
                };
                xhttp.open("POST", "comments/create", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(form);
                return false;
            }

            function createCommentOnComment(commentId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formcomment"+commentId));
                form.append("commentId", commentId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split(">");
                        document.getElementById("commentsoncomment"+commentId).innerHTML = genComment(responses[0], readerId, readerName, responses[1])
                        + document.getElementById("commentsoncomment"+commentId).innerHTML;
                        document.getElementById(focusId).innerHTML = "";
                        focusId = "";
                    }
                };
                xhttp.open("POST", "comments/create", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(form);
                return false;
            }

            function createPost(){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("createpost"));
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split(">");
                        document.getElementById("posts").innerHTML = genPost(responses[0], readerId, readerName, responses[1], responses[2])
                        + document.getElementById("posts").innerHTML;
                    }
                };
                xhttp.open("POST", "posts/create", true);
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
                +`<p id="optionscomment`+commentId+`"></p>`
                +`</a>`;
            }
        </script>

        <p>
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </p>

        <div>
            <h3>Write a new post:</h3>
            <form id="createpost" action="javascript:void(0);" onsubmit="createPost()">
                <p>Title</p>
                <input name="title" type="text"></input>
                <p>Content:<p>
                <input name="content" type="text"></input>
                <input type="submit"></input>
            </form>
        </div>
    
        <a id="posts">
        @foreach ($posts as $post)
            @include('components/post', ['post' => $post])
        @endforeach
        </a>

        <p>
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </p>
    </x-slot>
</x-contentLayout>

</html>
