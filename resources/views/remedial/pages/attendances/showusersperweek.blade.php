@extends('remedial.layouts.master')

@section('title', 'Attendance Detail')

@section('content')
<style>
    .table-sm td,
    .table-sm th {
        white-space: nowrap;
        padding: 0.5rem;
    }

</style>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <h5 class="card-header">{{ $user->name }}'s Lessons for Week {{ $week->week_number }}</h5>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Form</th>
                            <th>Subject</th>
                            <th>Lesson</th>
                            @can('admin')
                                <th>Action</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @if ($attendances->isEmpty())
                            <tr>
                                <td style="text-align: center" colspan="5">No lesson found!</td>
                            </tr>
                        @else
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td style="white-space: nowrap">{{ date('D d/m/y', strtotime($attendance->created_at)) }}</td>
                                    <td style="white-space: nowrap">{{ $attendance->form->name }}</td>
                                    <td>{{ $attendance->subject->name }}</td>
                                    <td>
                                        @if (in_array($attendance->lesson->name, ['L1', 'L2', 'L3', 'Morning', 'Evening']))
                                            @if ($attendance->lesson->name === 'Morning')
                                                6:10-7:10 am
                                            @elseif ($attendance->lesson->name === 'Evening')
                                                6:30-7:30 pm
                                            @else
                                            {{ \Carbon\Carbon::parse($attendance->lesson->start)->format('g:i a') }} - {{ \Carbon\Carbon::parse($attendance->lesson->end)->format('g:i a') }}

                                            @endif
                                        @elseif ($attendance->lesson->name === 'Practical')
                                            Practical
                                        @else
                                            {{ $attendance->lesson->name }}
                                        @endif
                                    </td>

                                    @can('admin')
                                    <td>
                                        <a href="#" class="text-danger delete-btn" data-attendance-id="{{ $attendance->id }}" data-toggle="modal" data-target="#deleteModal">
                                            {{-- <i class="fas fa-trash-alt"></i> --}}
                                            <i class='menu-icon tf-icons bx bxs-trash'></i>

                                        </a>
                                    </td>
                                    @endcan
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Display comments below the table -->
    <div class="card mt-3">

        <div class="card-body">
            <ul style="list-style-type: none; padding-left: 0;">
                @foreach($comments as $comment)
                    <li style="color: red; font-size: small;">- {{ $comment->comment }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            // Delete Button Click Event
            $('.delete-btn').on('click', function() {
                var attendanceId = $(this).data('attendance-id');
                deleteAttendance(attendanceId);
            });

            // Function to handle Attendance Deletion
            function deleteAttendance(attendanceId) {

                var confirmed = confirm("Are you sure you want to delete this record?");


                if (confirmed) {
                    // Perform the AJAX request for deletion
                    $.ajax({
                        url: "{{ url('remedial/attendance') }}/" + attendanceId + "/delete",
                        type: "DELETE",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            // Display success or error message using toastr
                            if (response.message) {
                                // Display success message
                                toastr.success(response.message, "", { timeOut: 5000 });
                            } else {
                                toastr.error(response.message);
                            }

                            location.reload();
                        },
                        error: function(error) {
                            // Display error message
                            toastr.error("Error: Unable to delete attendance record.");
                        }
                    });
                } else {
                    // Display info message
                    toastr.info("Your attendance record is safe!");
                }
            }

            // Toastr configuration
            toastr.options = {
                positionClass: 'toast-top-center',
                timeOut: 5000, // 5 seconds
            };
        });
    </script>
@endsection
