@extends('remedial.layouts.master')
@section('title', 'Create Comment')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Comment</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('comment.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="week_id" class="form-label">Week</label>
                            <select name="week_id" id="week_id" class="form-select @error('week_id') is-invalid @enderror">
                                <option value="">Select a week</option>
                                @foreach ($weeks as $week)
                                    <option value="{{ $week->id }}" {{ old('week_id') == $week->id ? 'selected' : '' }}>
                                        {{ $week->week_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('week_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea name="comment" id="comment" class="form-control @error('comment') is-invalid @enderror">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
