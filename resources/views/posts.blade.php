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
        
        <script>
            focusId = ""; {{-- //element that is being displayed because the user has clicked on something --}}
            readerId = {{$user->profile->id}}; {{-- // profile id of the user --}}
            readerName = "{{$user->profile->name}}"; {{-- //profile name of the user --}}
            readerAuth = "{{$user->profile->auth}}"; {{-- //whether the user is a 'user' or an 'admin' (for displaying options, actual authorisation is done in back-end) --}}

            {{-- // place reply, edit, delete options on a post where appropriate. also remove options from previously focused item if any --}}
            function placePostOptions(postId, authorId, title, content) {
                if(focusId != "optionspost"+postId){ {{-- // if selecting new focus --}}
                    if (focusId != "") { {{-- // if something else previously focused --}}
                        document.getElementById(focusId).innerHTML = ""; {{-- // remove previous focus --}}
                    }
                    comp = "<a onclick=placeCommentOnPostInput('"+postId+"')>Reply</a> "; {{-- // construct Reply button html --}}
                    if(authorId == readerId){ {{-- // check if user is allowed to edit (is original author) --}}
                        comp += `<a onclick="placeEditPostInput('`+postId+`', '`+title+`', '`+content+`')">Edit</a> `; {{-- // construct edit button html --}}
                    }
                    if(authorId == readerId || readerAuth == 'admin'){ {{-- // check if user is allowed to delete (is original author or admin) --}}
                        comp += genDeletePostButton(postId); {{-- // construct delete button html --}}
                    }
                    document.getElementById("optionspost"+postId).innerHTML = comp; {{-- // inject html options --}}
                    focusId = "optionspost"+postId; {{-- // assign new focus --}}
                } else{ {{-- // if selecting same focus as previous --}}
                    document.getElementById(focusId).innerHTML = ""; {{-- // remove focus --}}
                    focusId = "";
                }
            }

            {{-- // place reply, edit, delete options on a comment where appropriate. also remove options from previously focused item if any --}}
            function placeCommentOptions(commentId, authorId, content) {
                if(focusId != "optionscomment"+commentId){ {{-- // if selecting new focus --}}
                    if (focusId != "") { {{-- // if something else previously focused --}}
                        document.getElementById(focusId).innerHTML = ""; {{-- // remove previous focus --}}
                    }
                    comp = "<a onclick=placeCommentOnCommentInput('"+commentId+"')>Reply</a> "; {{-- // construct Reply button html --}}
                    if(authorId == readerId){ {{-- // check if user is allowed to edit (is original author) --}}
                        comp += `<a onclick="placeEditCommentInput('`+commentId+`', '`+content+`')">Edit</a> `; {{-- // construct edit button html --}}
                    }
                    if(authorId == readerId || readerAuth == 'admin'){ {{-- // check if user is allowed to delete (is original author or admin) --}}
                        comp += genDeleteCommentButton(commentId); {{-- // construct delete button html --}}
                    }
                    document.getElementById("optionscomment"+commentId).innerHTML = comp; {{-- // inject html options --}}
                    focusId = "optionscomment"+commentId; {{-- // assign new focus --}}
                } else{ {{-- // if selecting same focus as previous --}}
                    document.getElementById(focusId).innerHTML = ""; {{-- // remove focus --}}
                    focusId = "";
                }
            }

            {{-- // construct delete post button --}}
            function genDeletePostButton(postId){
                return ` <a onclick="deletePost('`+postId+`')">Delete</a>`;
            }

            {{-- // construct delete comment button --}}
            function genDeleteCommentButton(commentId){
                return ` <a onclick="deleteComment('`+commentId+`')">Delete</a>`;
            }

            {{-- // injects a html form to comment on the specified post --}}
            function placeCommentOnPostInput(postId){
                document.getElementById("optionspost"+postId).innerHTML = 
                `<form id=formpost`+postId+` action='javascript:void(0);' onsubmit="createCommentOnPost('`+postId+`')">`
                +`@csrf`
                +`<input type='text' name='content'></input>`
                +`<input type='submit' value='Reply'></input>`
                +`</form>`;
            }

            {{-- // injects a html form to comment on the specified comment --}}
            function placeCommentOnCommentInput(commentId){
                document.getElementById("optionscomment"+commentId).innerHTML = 
                `<form id=formcomment`+commentId+` action='javascript:void(0);'  onsubmit="createCommentOnComment('`+commentId+`')">`
                +`@csrf`
                +`<input type='text' name='content'></input>`
                +`<input type='submit' value='Reply'></input>`
                +`</form>`;
            }

            {{-- // injects a html form to edit the specified comment --}}
            function placeEditCommentInput(commentId, content){
                document.getElementById("optionscomment"+commentId).innerHTML = 
                `<form id=formcomment`+commentId+` action='javascript:void(0);' onsubmit="editComment('`+commentId+`')">`
                    +`@csrf`
                +"<input type='text' name='content' value='"+content+"'></input>"
                +"<input type='submit' value='Edit'></input>"
                +"</form>";
            }

            {{-- // injects a html form to edit the specified post --}}
            function placeEditPostInput(postId, title, content){
                document.getElementById("optionspost"+postId).innerHTML = 
                `<form id=formpost`+postId+` action='javascript:void(0);' onsubmit="editPost('`+postId+`')">`
                    +`@csrf`
                +"<input type='text' name=title value='"+title+"'></input>"
                +"<input type='text' name=content value='"+content+"'></input>"
                +"<input type='submit' value='Edit'></input>"
                +"</form>";
            }

            {{-- // constructs a html post and options area for that post (but not comments on that post) --}}
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

            {{-- // constructs a html comment and options area for that comment (but not comments on that comment) --}}
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

            {{-- // constructs a html image linking to a profile's avatar and redirecting to the default if not found --}}
            function getAvatar(profileId){
                return `<img src="{{Storage::disk('public')->url('avatars')}}/`+profileId+`.png" `
                +`onerror="this.onerror=null; this.src='{{Storage::disk('public')->url('avatars/default.png')}}'" `
                +`width="40" height="40">`;
            }

            {{-- // gets the  input form under the specified post and submits it via xhttp request --}}
            {{-- // replaces the html post with the response from server (if successful) --}}
            function editPost(postId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formpost"+postId));
                form.append("post_id", postId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split("<a>");
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

            {{-- // gets the input form under the specified comment and submits it via xhttp request --}}
            {{-- // replaces the html post with the response from server (if successful) --}}
            function editComment(commentId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formcomment"+commentId));
                form.append("comment_id", commentId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split("<a>");
                        if(responses.length == 2){
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

            {{-- // submits the delete request via xhttp request --}}
            {{-- // removes the html post if server response indicates success --}}
            function deletePost(postId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData();
                form.append("post_id", postId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        focusId = "";
                        if(this.responseText == 'True'){
                            document.getElementById("post"+postId).outerHTML = null;
                            document.getElementById("commentsonpost"+postId).outerHTML = null;
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

            {{-- // submits the delete request via xhttp request --}}
            {{-- // removes the html comment if server response indicates success --}}
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

            {{-- // gets the input form under the specified post and submits it via xhttp request --}}
            {{-- // contruct a html comment under the target post from the response from server (if successful) --}}
            function createCommentOnPost(postId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formpost"+postId));
                form.append("post_id", postId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split("<a>");
                        if(responses.length == 2){
                            document.getElementById("commentsonpost"+postId).innerHTML = genComment(responses[0], readerId, readerName, responses[1])
                            + `<ul id="commentsoncomment`+responses[0]+`"></ul>`
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

            {{-- // gets the input form under the specified comment and submits it via xhttp request --}}
            {{-- // construct a html comment under the target comment from the response from server (if successful) --}}
            function createCommentOnComment(commentId){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("formcomment"+commentId));
                form.append("comment_id", commentId);
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split("<a>");
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

            {{-- // gets the create post form and submits it via xhttp request --}}
            {{-- // construct a html post at the top of the posts from the response from server (if successful) --}}
            function createPost(){
                var xhttp = new XMLHttpRequest();
                var form = new FormData(document.getElementById("createpost"));
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        responses = this.responseText.split("<a>");
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
            
            {{-- // register a listener to act when someone else comments on this user's content --}}
            {{-- // output an alert when the listener is triggered --}}
            Echo.channel('item-commented-'+readerId).listen('ItemCommented', (e) => {
                alert('Someone has commented on your content');
            });
            
            {{-- // register a listener to act when someone else with admin access deletes this user's content --}}
            {{-- // output an alert when the listener is triggered --}}
            Echo.channel('item-deleted-'+readerId).listen('ItemDeleted', (e) => {
                alert('An admin has deleted some of your content');
            });
        </script>











        <div>
            {{-- // form to search for posts written by a user with the specified name --}}
            <form method="POST" action="/posts">
                @csrf
                <p>
                    <input id="searchName" type="Search" name="searchName" class="@error('title') is-invalid @enderror">
                    <input type="Submit" value="Search by author">
                </p>
            </form>
        </div>

        <div>
            {{-- // construct pagination links --}}
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </div>

        <div class="border-solid rounded-lg my-4 p-4">
            {{-- // form to create new posts --}}
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
    
        {{-- // construct all posts on this page --}}
        <a id="posts">
        @foreach ($posts as $post)
            @include('components/post', ['post' => $post])
        @endforeach
        </a>

        <p>
            {{-- // construct pagination links --}}
            Pages:
            {{ $posts->links('pagination::bootstrap-4') }}
        </p>
        
    </x-slot>
</x-contentLayout>
</html>
