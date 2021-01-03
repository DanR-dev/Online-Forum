<!DOCTYPE html>
<html>

    <body>
    {{-- Recursively loop through comments, eqivalent to pre-order tree traversal --}}
        @foreach ($commentable->comments as $comment)
        {{-- Write this comment --}}
            <script>document.write(genComment({{$comment->id}}, {{$comment->profile->id}}, "{{$comment->profile->name}}", "{{$comment->content}}"));</script>
            <ul id="commentsoncomment{{$comment->id}}">
                {{-- Recurse on comments of this comment --}}
                @include('components/commentsOf', ['commentable' => $comment])
            </ul>
        @endforeach
    </body>

</html>
