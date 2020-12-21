<!DOCTYPE html>
<html>

<body>
    <ul type="circle">
        @foreach ($commentable->comments as $comment)
            <li onclick="placeCommentableOptions('comment{{ $comment->id }}', '{{ $comment->profile->id }}')">
                <i>{{ $comment->profile->name }}</i> : {{ $comment->content }}
            </li>
            <p id="comment{{ $comment->id }}"></p>
            @include('components/commentsOf', ['commentable' => $comment])
        @endforeach
    </ul>
</body>

</html>
