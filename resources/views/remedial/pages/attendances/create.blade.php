@extends('remedial.layouts.master')
@section('title', 'New Record')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if (session('success'))
                        <div class="alert alert-success mt-3" style="padding: 5px;">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card-header">Add new Attendance Record</div>

                    <div class="card-body">

                        <form id="create_attendance">
                            @csrf
                            <div>
                                <label for="smallSelect" class="form-label">Teacher</label>
                                <select id="selectteacher" name="teacher" required
                                    class="form-select selectteacher form-select-sm">
                                    <option>Select Teacher</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                @error('teacher')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="smallSelect" class="form-label">Class</label>
                                <select id="smallSelect" name="class" class="form-select selectclass form-select-sm">
                                    <option>Select Class</option>
                                    @foreach ($forms as $form)
                                        <option value="{{ $form->id }}">{{ $form->name }}</option>
                                    @endforeach
                                </select>
                                @error('class')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="smallSelect" class="form-label">Subject</label>
                                <select id="smallSelect" name="subject" class="form-select form-select-sm">
                                    <option>Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                @error('class')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="smallSelect" class="form-label">Lesson</label>
                                <select id="smallSelect" name="lesson" class="form-select form-select-sm">
                                    <option>Select Lesson</option>
                                    @foreach ($lessons as $lesson)
                                        @if ($lesson->name === 'Practical')
                                            <option value="{{ $lesson->id }}">Practical</option>
                                        @elseif (in_array($lesson->name, ['L1', 'L2', 'L3']))
                                            <option value="{{ $lesson->id }}">
                                                {{ strtoupper($lesson->name) }} ( {{ \Carbon\Carbon::parse($lesson->start)->format('g:i a') }} - {{ \Carbon\Carbon::parse($lesson->end)->format('g:i a') }} )
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                


                                @error('lesson')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <label for="smallSelect" class="form-label">Week</label>
                                <select id="smallSelect" name="week" class="form-select form-select-sm">
                                    <option>Select Week</option>
                                    @foreach ($weeks as $week)
                                        <option value="{{ $week->id }}">Week {{ $week->week_number }}</option>
                                    @endforeach
                                </select>
                                @error('week')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" name="status" type="checkbox" value=""
                                    id="status" />
                                <label class="form-check-label" for="defaultCheck1"> Make-Up </label>
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary mt-3">Save</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Your existing HTML code -->

@section('scripts')
    <script>
        $(document).ready(function() {

            $('.selectteacher').select2();
            $('.selectclass').select2();

            var form = $("#create_attendance");
            var submitBtn = form.find("button[type=submit]"); // Get the submit button

            form.on("submit", function(event) {
                event.preventDefault();

                // Disable the submit button while the form is being submitted
                submitBtn.prop('disabled', true);

                var data = $(this).serialize();
                $.ajax({
                    url: "{{ route('attendance.store') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        // Re-enable the submit button after the form is successfully submitted
                        submitBtn.prop('disabled', false);

                        if (response.success) {
                            swal({
                                title: "Success!",
                                text: response.message,
                                type: "success",
                                showConfirmButton: false
                            }).then(function() {
                                form.trigger('reset');
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
                        // Re-enable the submit button if there's an error
                        submitBtn.prop('disabled', false);

                        // Handle the error response
                        console.log(error);
                    }
                })
            })
        });
    </script>
@endsection
