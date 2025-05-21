@extends('lecturar.dashboard')

@section('title', 'Edit PLO')

@section('content')
<div class="container mt-4">
    <h2>Edit PLO {{ $number }}</h2>
    <form>
        <div class="form-group">
            <label for="plo_title">Title</label>
            <input type="text" class="form-control" id="plo_title" name="plo_title" value="">
        </div>
        <div class="form-group">
            <label for="plo_description">Description</label>
            <textarea class="form-control" id="plo_description" name="plo_description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('duty.dashboard', ['duty' => 9]) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 