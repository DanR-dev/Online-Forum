<form method = "POST" action = "/editForm">
    @csrf
    @method('PUT')
    <label for="title">Role Title</label>
    <input id="title" name="title" type="text" class="@error('title') is-invalid @enderror">
    <label for="title">New Title</label>
    <input id="newTitle" name="newTitle" type="text" class="@error('newTitle') is-invalid @enderror">

    <input type="submit" name="mode" value="Edit" class="btn btn-dark btn-block">
    <input type="submit" name="mode" value="Delete" class="btn btn-dark btn-block">

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