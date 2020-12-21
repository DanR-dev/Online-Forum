<form method="POST" action="/createForm">
    @csrf
    <label for="title">Role Title</label>
    <input id="title" name="title" type="text" class="@error('title') is-invalid @enderror">

    <input type="submit" name="send" value="Submit" class="btn btn-dark btn-block">

    @error('title')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</form>
