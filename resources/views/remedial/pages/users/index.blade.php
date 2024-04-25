@extends('remedial.layouts.master')
@section('title', 'Attendance')

@section('content')
  <div class="col-12 mx-auto">
    <div class="card">
      @can('admin')
        <h5 class="card-header">
          <span>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Add New</a>
          </span>
          <span>
            <form action="{{ route('attendance.deleteAll') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmDeleteAll()">Delete All Remedial Records!</button>
            </form>
          </span>
        </h5>
      @endcan

      @if (Session::has('message'))
        <div class="alert alert-success">
          {{ Session::get('message') }}
        </div>
      @endif

      <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover table-striped" id="users-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Code</th>
              @can('super')
                <th>Action</th>
              @endcan
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @php $sl = 0 @endphp
            @foreach ($users as $user)
              <tr>
                <td>{{ $users->firstItem() + $loop->index }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->email }}</td>
                <td>
                  @if ($user->id == auth()->user()->id || Gate::check('admin'))
                    {{ $user->code }}
                  @else
                    -
                  @endif
                </td>
                @can('super')
                  <td>
                    <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form style="display: inline;" action="{{ route('users.destroy', ['user' => $user->id]) }}" method="post">
                      @csrf
                      @method('delete')
                      <button onclick="return confirmUserDelete()" class="btn btn-danger btn-sm" type="submit">Delete</button>
                    </form>
                  </td>
                @endcan
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#users-table').DataTable();
    });

    function confirmUserDelete() {
      return confirm('Warning: Deleting this teacher will also delete all remedial lessons associated. This action cannot be undone. Are you sure you want to proceed?');
    }

    function confirmDeleteAll() {
      return confirm('Are you sure you want to delete all attendances fo all teachers? This action cannot be undone.');
    }
  </script>
@endsection

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
