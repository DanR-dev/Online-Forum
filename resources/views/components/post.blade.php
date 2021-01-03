<!DOCTYPE html>
<html>
    {{-- write the post --}}
    <script>document.write(genPost({{$post->id}}, {{$post->profile->id}}, "{{$post->profile->name}}", "{{$post->title}}", "{{$post->content}}"));</script>
    <ul id="commentsonpost{{$post->id}}">
        {{-- list comments on that post --}}
        @include('components/commentsOf', ['commentable' => $post])
    </ul>
</html>