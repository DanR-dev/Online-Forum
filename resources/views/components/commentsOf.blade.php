<!DOCTYPE html>
<html>

<body>
    <ul type="circle">
        @foreach ($commentable->comments as $comment)
            <li>
                <p><i>{{ $comment->profile->name }}</i> : {{ $comment->content }}</p>
            </li>
            @include('components/commentsOf', ['commentable' => $comment])
        @endforeach
    </ul>
</body>

</html>
