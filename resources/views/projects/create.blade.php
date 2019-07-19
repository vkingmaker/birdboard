@extends('layouts.app')

@section('content')

<form method="POST" action="/projects">
    @csrf

    <h1 class="heading">Create a Project</h1>

    <div class="field">
        <label for="title">Title</label>

        <div class="form-group">
            <input type="text" id="title" class="form-control" name="title" placeholder="title">
        </div>
    </div>

    <div class="field">
    <label for="description">Description</label>

    <div class="form-group">
        <textarea type="text" class="form-control" name="description" placeholder="description" id="description"></textarea>
    </div>
    </div>
    <div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary form-control">Create Project</button>
        </div>
    </div>
</form>
@endsection
