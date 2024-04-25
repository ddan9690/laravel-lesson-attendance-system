@extends('remedial.layouts.master')

@section('title', 'Create Comment')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Comment</h3>
                    <!-- Add a back link button -->
                    <a href="{{ route('comment.index') }}" class="btn btn-sm btn-secondary">Back</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form id="create_comment">
                        @csrf

                        <div class="mb-3">
                            <label for="week_id" class="form-label">Week</label>
                            <select name="week_id" id="week_id" class="form-select @error('week_id') is-invalid @enderror">
                                <option value="">Select a week</option>
                                @foreach ($weeks as $week)
                                    <option value="{{ $week->id }}" {{ old('week_id') == $week->id ? 'selected' : '' }}>
                                       Week {{ $week->week_number }}
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

                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                var form = $("#create_comment");

                form.on("submit", function(event) {
                    event.preventDefault();

                    var data = form.serialize();

                    $.ajax({
                        url: "{{ route('comment.store') }}",
                        type: "POST",
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                swal({
                                    title: "Success!",
                                    text: response.message,
                                    type: "success",
                                    showConfirmButton: false
                                }).then(function() {
                                    // Optionally, you can redirect or perform other actions after success
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: "Warning!",
                                    text: response.message,
                                    type: "warning",
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            // Handle the error response
                            swal("Error", "Failed to submit comment. Please try again.", "error");
                        }
                    });
                });
            });
        </script>
    @endsection
@endsection
