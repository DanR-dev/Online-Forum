<!DOCTYPE html>
<html>
@csrf
<x-contentLayout>
    <x-slot name="title">
        Account Options
    </x-slot>
    <x-slot name="loggedIn">
        {{ $loggedIn }}
    </x-slot>
    <x-slot name="content">
        @if($loggedIn == True)
            {{-- Logout form --}}
            <form method = "POST" action = "/logout">
                @csrf
                <div><input type="submit" value="logout" class="btn btn-dark btn-block"></div>
            </form>
            {{-- User details --}}
            <div>Name : {{Auth::user()->profile->name}}</div>
            <div>Email : {{Auth::user()->email}}</div>
            <div>
                {{-- User avatar (or default) --}}
                Avatar : 
                @if(Storage::disk('public')->exists('avatars/'.Auth::user()->profile->id.'.png'))
                    <br><img src="{{Storage::disk('public')->url('avatars/'.Auth::user()->profile->id.'.png')}}" width="160" height="160">
                @else
                    <br><img src="{{Storage::disk('public')->url('avatars/default.png')}}" width="160" height="160">
                @endif
            </div>
            {{-- Set avatar form --}}
            <form method = "POST" enctype="multipart/form-data" action = "/avatar/set">
                @csrf
                <div><label for="avatar">Set Avatar: (must be 1:1 ratio)</label></div>
                <div><input id="avatar" name="avatar" type="file" placeholder="Choose image"></div>
                <div><input type="submit" value="Upload" class="btn btn-dark btn-block"></div>
            </form>
        @else
            {{-- User login form --}}
            <form method = "POST" action = "/login">
                @csrf
                <div><label for="email">Email</label></div>
                <div><input id="email" name="email" type="text"></div>
                <div><label for="password">Password</label></div>
                <div><input id="password" name="password" type="password"></div>
                <div><input type="submit" value="login" class="btn btn-dark btn-block"></div>
            </form>
        @endif

        {{-- List any errors from form submission --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </x-slot>
</x-contentLayout>
</html>