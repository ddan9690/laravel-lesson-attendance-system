@extends('remedial.layouts.master')
@section('title', 'Attendance')
@section('content')


    <div class="col-md-6 mx-auto">
        <div class="card">

            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">Add
                New Class</button>
            <div id="successMessage" class="alert alert-success" style="display: none;"></div>

            <div class="table-responsive text-nowrap">

                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php $sl=0 @endphp
                        @foreach ($forms as $form)
                            <tr>
                                <td>{{ $forms->firstItem() + $loop->index }}</td>
                                <td>{{ $form->name }}</td>
                                <td>

                                    <button type="submit" class="btn btn-sm btn-outline-danger delete-form" data-id="{{ $form->id }}">
                                        Delete
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex align-items-center mt-3">
                    {!! $forms->links() !!}
                </div>
            </div>
        </div>
    </div>


    {{-- add new class modal --}}
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add new Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <!-- Add any additional form fields here -->
                        </div>
                        <div class="row g-2">
                            <div class="col mb-0">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Class Name" aria-describedby="name-help" />
                                {{-- <small id="name-help" class="form-text text-muted">This field is required.</small> --}}
                                <small class="invalid-feedback" style=" color: red;">Please enter a name.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" id="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Function to handle form submission
            $('#createForm').submit(function(event) {
                event.preventDefault(); // Prevent the form from submitting normally

                // Clear previous error messages
                $('#name').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Get form data
                var formData = new FormData(this);
                console.log(formData);

                $.ajax({
                    url: '{{ route('form.store') }}',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle success response
                        if (response.success) {
                            // Show success message
                            $('#successMessage').text(response.message).show();

                            // Reset the form
                            $('#createForm')[0].reset();

                            // Close the modal
                            $('#basicModal').modal('hide');
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        // Handle error response
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;

                            if (errors.hasOwnProperty('name')) {
                                // Display the error message for name input
                                $('#name').addClass('is-invalid');
                                $('.invalid-feedback').text(errors.name[0]);
                            }
                        }
                    }
                });
            });

            $(document).on('click', '.delete-form', function() {
            var formId = $(this).data('id');

    // Use Sweet Alert confirmation dialog
    swal({
        title: 'Are you sure?',
        text: 'This record will be permanently deleted!',
        icon: 'warning',
        buttons: ["Cancel", "Delete"],
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/remedial/form/{id}/delete', // Update the URL to match your route
                type: 'DELETE',
                dataType: 'json',
                data: {
                    _token: $('input[name="_token"]').val()
                },
                success: function(response) {
                    if (response.success) {
                        $('#successMessage').text('Form deleted successfully').show();
                        location.reload();
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    });
});


        });
    </script>
@endsection
