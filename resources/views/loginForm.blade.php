<form method = "POST" action = "/login">
    @csrf
    <div><label for="email">Email</label></div>
    <div><input id="email" name="email" type="text"></div>
    <div><label for="password">Password</label></div>
    <div><input id="password" name="password" type="password"></div>

    @if($loggedIn == True)
    <div><input type="submit" name="mode" value="logout" class="btn btn-dark btn-block"></div>
    @else
    <div><input type="submit" name="mode" value="login" class="btn btn-dark btn-block"></div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</form>