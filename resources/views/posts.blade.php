<!DOCTYPE html>
<html>
@csrf
<x-contentLayout>
    <x-slot name="title">
        The Place of Learning
    </x-slot>
    <x-slot name="loggedIn">
        {{ $loggedIn }}
    </x-slot>
    <x-slot name="content">

        <div>
            <form method="POST" action="/posts">
                @csrf
                <p>
                    <input id="searchName" type="Search" name="searchName" class="@error('title') is-invalid @enderror">
                    <input type="Submit" value="Search by author">
                </p>
            </form>
        </div>

        <div>
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>

        <div class="border-solid rounded-lg my-4 p-4">
            <h3>Write a new post:</h3>
            <form id="createpost" action="javascript:void(0);" onsubmit="createPost()">
                @csrf
                <p>Title:</p>
                <input name="title" type="text"></input>
                <p>Content:</p>
                <input name="content" type="text"></input>
                <input type="submit" value="Post"></input>
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
                +`@csrf`
                +`<input type='text' name='content'></input>`
                +`<input type='submit' value='Reply'></input>`
                +`</form>`;
            }

            function placeCommentOnCommentInput(commentId){
                document.getElementById("optionscomment"+commentId).innerHTML = 
                `<form id=formcomment`+commentId+` action='javascript:void(0);'  onsubmit="createCommentOnComment('`+commentId+`')">`
                    +`@csrf`
                +`<input type='text' name='content'></input>`
                +`<input type='submit' value='Reply'></input>`
                +`</form>`;
            }

            function placeEditCommentInput(commentId, content){
                document.getElementById("optionscomment"+commentId).innerHTML = 
                `<form id=formcomment`+commentId+` action='javascript:void(0);' onsubmit="editComment('`+commentId+`')">`
                    +`@csrf`
                +"<input type='text' name='content' value='"+content+"'></input>"
                +"<input type='submit' value='Edit'></input>"
                +"</form>";
            }

            function placeEditPostInput(postId, title, content){
                document.getElementById("optionspost"+postId).innerHTML = 
                `<form id=formpost`+postId+` action='javascript:void(0);' onsubmit="editPost('`+postId+`')">`
                    +`@csrf`
                +"<input type='text' name=title value='"+title+"'></input>"
                +"<input type='text' name=content value='"+content+"'></input>"
                +"<input type='submit' value='Edit'></input>"
                +"</form>";
            }

            function genPost(postId, authorId, authorName, title, content){
                return `<a id="post`+postId+`">`
                +`<div onclick="placePostOptions('`+postId+`', '`+authorId+`', '`+title+`', '`+content+`')" `
                +`class="border-dashed rounded-lg p-4">`
                +`<h3>`
                +getAvatar(authorId)
                +`<i>`+authorName+`</i> : `+title+`</h3>`
                +content
                +`</div>`
                +`<p id="optionspost`+postId+`" class="text-blue-600"></p>`
                +`</a>`;
            }

            function genComment(commentId, authorId, authorName, content){
                return `<a id=comment`+commentId+`>`
                +`<p>`
                +`<li onclick="placeCommentOptions('`+commentId+`', '`+authorId+`', '`+content+`')" `
                +`class="border-dashed border-gray-400 list-none rounded-lg p-4">`
                +getAvatar(authorId)
                +`<i>`+authorName+`</i> : `+content
                +`</li>`
                +`</p>`
                +`<p id="optionscomment`+commentId+`" class="text-blue-600"></p>`
                +`</a>`;
            }

            function getAvatar(profileId){
                return `<img src="{{Storage::disk('public')->url('avatars')}}/`+profileId+`.png" `
                +`onerror="this.onerror=null; this.src='{{Storage::disk('public')->url('avatars/default.png')}}'" `
                +`width="40" height="40">`;
            }

            function editPost(postId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formpost"+postId));
                form.append("post_id", postId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split(">");
                        if(responses.length == 3){
                            document.getElementById("post"+postId).outerHTML = genPost(responses[0], readerId, readerName, responses[1], responses[2]);
                            document.getElementById(focusId).innerHTML = "";
                            focusId = "";
                        } else{
                            alert("The new content and title of your post cannot be blank (HTML tags will be ignored)");
                        }
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
                form.append("comment_id", commentId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if(responses.length == 2){
                            responses = this.responseText.split(">");
                            document.getElementById("comment"+commentId).outerHTML = genComment(responses[0], readerId, readerName, responses[1]);
                            document.getElementById(focusId).innerHTML = "";
                            focusId = "";
                        } else{
                            alert("The new content of your comment cannot be blank (HTML tags will be ignored)");
                        }
                    }
                };
                xhttp.open("POST", "comments/edit", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(form);
                return false;
            }

            function deletePost(postId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData();
                form.append("post_id", postId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        focusId = "";
                        if(this.responseText == 'True'){
                            document.getElementById("post"+postId).outerHTML = null;
                        } else{
                            alert("Something went wrong when deleting this post");
                        }
                    }
                };
                xhttp.open("POST", "posts/delete", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(form);
                return false;
            }

            function deleteComment(commentId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData();
                form.append('comment_id', commentId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        focusId = "";
                        if(this.responseText == 'True'){
                            document.getElementById("comment"+commentId).outerHTML = null;
                        } else{
                            alert("Something went wrong when deleting this comment");
                        }
                    }
                };
                xhttp.open("POST", "comments/delete", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(form);
                return false;
            }

            function createCommentOnPost(postId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formpost"+postId));
                form.append("post_id", postId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split(">");
                        if(responses.length == 2){
                            document.getElementById("commentsonpost"+postId).innerHTML = genComment(responses[0], readerId, readerName, responses[1])
                            + document.getElementById("commentsonpost"+postId).innerHTML;
                            document.getElementById(focusId).innerHTML = "";
                            focusId = "";
                        } else{
                            alert("The content of your comment cannot be blank (HTML tags will be ignored)");
                        }
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
                form.append("comment_id", commentId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split(">");
                        if(responses.length == 2){
                            document.getElementById("commentsoncomment"+commentId).innerHTML = genComment(responses[0], readerId, readerName, responses[1])
                            + `<ul id="commentsoncomment`+responses[0]+`"></ul>`
                            + document.getElementById("commentsoncomment"+commentId).innerHTML;
                            document.getElementById(focusId).innerHTML = "";
                            focusId = "";
                        } else{
                            alert("The content of your comment cannot be blank (HTML tags will be ignored)");
                        }
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
                        if(responses.length == 3){
                            document.getElementById("posts").innerHTML = genPost(responses[0], readerId, readerName, responses[1], responses[2])
                            + `<ul id="commentsonpost`+responses[0]+`"></ul>`
                            + document.getElementById("posts").innerHTML;
                        } else{
                            alert("The title and content of your post cannot be blank (HTML tags will be ignored)");
                        }
                    }
                };
                xhttp.open("POST", "posts/create", true);
                xhttp.setRequestHeader("X-Csrf-Token", document.getElementsByName("_token")[0].value);
                xhttp.send(form);
                return false;
            }
            
            Echo.channel('item-commented-'+readerId).listen('ItemCommented', (e) => {
                alert('Someone has commented on your content');
            });
            Echo.channel('item-deleted-'+readerId).listen('ItemDeleted', (e) => {
                alert('An admin has deleted some of your content');
            });
        </script>
    </x-slot>
</x-contentLayout>
</html>
