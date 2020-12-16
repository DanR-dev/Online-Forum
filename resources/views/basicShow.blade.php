<!DOCTYPE html>
<html>
<x-basicLayout>
    <x-slot name="title">
        Posts and comments and stuff
    </x-slot>
    <x-slot name="content">
        <ul>
            @foreach ($posts as $post)
                <li>
                    <h3>{{ $post->title }}</h3>
                    {{ $post->content }}
                    <br>-<i>{{ $post->profile->name }}</i>
                    <ul type="circle">
                        @foreach ($post->comments as $comment)
                            <li>
                                <p><i>{{ $comment->profile->name }}</i> : {{ $comment->content }}</p>
                                <ul type="square">
                                    @foreach ($comment->comments as $subcomment)
                                        <li>
                                            <p><i>{{ $subcomment->profile->name }}</i> : {{ $subcomment->content }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </x-slot>
</x-basicLayout>

</html>
