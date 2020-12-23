<!DOCTYPE html>
<html>

<body>
        @foreach ($commentable->comments as $comment)
            <script>document.write(genComment({{$comment->id}}, {{$comment->profile->id}}, "{{$comment->profile->name}}", "{{$comment->content}}"));</script>
            <ul id="commentsoncomment{{$comment->id}}" type="circle">
                @include('components/commentsOf', ['commentable' => $comment])
            </ul>
        @endforeach
</body>

</html>
