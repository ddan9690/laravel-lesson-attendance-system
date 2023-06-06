@extends('remedial.layouts.master')

@section('title', 'New Week')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            {{-- <strong>Error!</strong> Please fix the following issues: --}}
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card-header">Add New Week</div>

                    <div class="card-body">
                        <form id="create_week" action="{{ route('week.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="week_number" class="form-label">Week Number</label>
                                <input type="number" class="form-control" id="week_number" name="week_number" required>
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
