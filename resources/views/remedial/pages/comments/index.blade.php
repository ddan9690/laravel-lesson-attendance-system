@extends('remedial.layouts.master')
@section('title', 'Comments')
@section('content')
<div class="col-md-10 mx-auto">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-start align-items-center">
                <form action="{{ route('comment.index') }}" method="get" class="form-inline my-2 my-lg-0">
                    <span for="week">Select Week</span>
                    <div class="input-group">
                        <select name="week" id="week" class="form-control form-control-sm">
                            @foreach($weeks as $week)
                            <option value="{{ $week->id }}" {{ $selectedWeek == $week->id ? 'selected' : '' }}>
                                {{ $week->week_number }}
                            </option>
                            @endforeach
                        </select>
                        <div class="input-group-append ms-1">
                            <button type="submit" class="btn btn-secondary btn-sm">View</button>
                        </div>
                    </div>
                </form>
                @can('admin')
                <a href="{{ route('comment.create') }}" class="btn mt-4 btn-primary btn-sm mx-1">New Comment</a>
                @endcan
                <a href="{{ route('exportToPDF') }}" class="btn mt-4 btn-success btn-sm mx-1">
                    <i class="menu-icon tf-icons bx bxs-download"></i>
                </a>
            </div>


            <form action="{{ route('comment.deleteAll') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm"
                    onclick="return confirmDeleteAll()">Delete All Notices for this Term</button>
            </form>


        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="font-size: 10px">#</th>
                        <th style="font-size: 10px">Comment</th>
                        <th style="font-size: 10px"></th>
                        @can('admin')
                        <th style="font-size: 10px">Actions</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @if ($comments->count() > 0)
                        @for ($i = 0; $i < count($comments); $i++)
                            <tr>
                                <td style="font-size: 12px">{{ $i + 1 }}</td>
                                <td style="font-size: 12px">{{ $comments[$i]->comment }}</td>
                                @can('admin')
                                <td style="font-size: 12px">{{ $comments[$i]->user->name }}</td>
                                @endcan
                                @can('admin')
                                <td style="font-size: 12px">
                                    <a href="{{ route('comment.edit', $comments[$i]->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('comment.destroy', $comments[$i]->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                        @endfor
                    @else
                        <tr>
                            <td colspan="4" style="text-align: center">No notice found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{ $comments->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>


        function confirmDeleteAll() {
            return confirm('Are you sure you want to delete all notices for this term? This action cannot be undone.');
        }
    </script>
@endsection
