<!DOCTYPE html>
<html>

<body>
        @foreach ($commentable->comments as $comment)
            <script>document.write(genComment({{$comment->id}}, {{$comment->profile->id}}, "{{$comment->profile->name}}", "{{$comment->content}}"));</script>
            @if($comment->comments->count() > 0)
                <ul id="commentsoncomment{{$comment->id}}">
                    @include('components/commentsOf', ['commentable' => $comment])
                </ul>
            @endif
        @endforeach
</body>

</html>
