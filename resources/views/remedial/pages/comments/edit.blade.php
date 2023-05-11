@extends('remedial.layouts.master')
@section('title', 'Edit Comment')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Comment</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('comment.update', $comment->id) }}">
                        @csrf
                        @method('PUT')
                    
                        <div class="form-group">
                            <label for="week_id">Week</label>
                            <select name="week_id" id="week_id" class="form-control">
                                @foreach($weeks as $week)
                                    <option value="{{ $week->id }}" {{ $comment->week_id == $week->id ? 'selected' : '' }}>
                                        {{ $week->week_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea name="comment" id="comment" class="form-control" rows="5">{{ $comment->comment }}</textarea>
                        </div>
                    
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
