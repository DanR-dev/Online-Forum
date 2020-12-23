<!DOCTYPE html>
<html>
    <script>document.write(genPost({{$post->id}}, {{$post->profile->id}}, "{{$post->profile->name}}", "{{$post->title}}", "{{$post->content}}"));</script>
    <ul id="commentsonpost{{$post->id}}" type="circle">
        @include('components/commentsOf', ['commentable' => $post])
    </ul>
</html>