@extends('remedial.layouts.master')

@section('title', 'Attendance')

@section('content')
    <div class="col-md-6 mx-auto">
        <div class="card">
            <a href="{{ route('week.create') }}" class="btn btn-sm btn-primary">Add New Week</a>

            @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

            <div class="table-responsive text-nowrap">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>

                            <th>Wk  No.</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php $sl=0 @endphp
                        @foreach ($weeks as $week)
                            <tr>

                                <td>{{ $week->week_number }}</td>
                                <td>
                                    <form action="{{ route('week.destroy', $week->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirmDelete()">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex align-items-center mt-3">
                    {!! $weeks->appends(request()->query())->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this week?');
    }
</script>
@endsection
