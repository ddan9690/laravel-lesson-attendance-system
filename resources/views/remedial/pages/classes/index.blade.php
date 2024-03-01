@extends('remedial.layouts.master')
@section('title', 'Attendance')
@section('content')
    <div class="col-md-6 mx-auto">
        <div class="card">
            @can('jamadata')
            <a href="{{ route('form.create') }}" class="btn btn-sm btn-primary">Add New Class</a>
            @endcan

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
                                    {{-- <a href="{{ route('form.edit', ['id' => $form->id]) }}" class="btn btn-sm btn-outline-primary">Update</a> --}}
                                    <form action="{{ route('form.destroy', ['id' => $form->id]) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure? This will delete all remedial lessons for this class. This action cannot be undone!')">Delete</button>

                                    </form>
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
@endsection
