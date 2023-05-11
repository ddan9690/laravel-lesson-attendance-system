@extends('remedial.layouts.master')
@section('title', 'Attendance')
@section('content')
    <div class="col-md-10 mx-auto">
        <div class="card">
            @can('admin')
                <h5 class="card-header"><span><a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Add
                            New</a></span></h5>
            @endcan


            @if (Session::has('message'))
                <div class="alert alert-success">
                    {{ Session::get('message') }}
                </div>
            @endif
            <div class="table-responsive text-nowrap">
                <table class="table">
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
                        @php $sl=0 @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ ++$sl }}</td>
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
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('users.edit', ['user' => $user->id]) }}">Edit</a>
                                        <form style="display: inline;"
                                            action="{{ route('users.destroy', ['user' => $user->id]) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button onclick="return confirm('Are you want to delete?')"
                                                class="btn btn-danger btn-sm" type="submit">Delete</button>
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach


                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
