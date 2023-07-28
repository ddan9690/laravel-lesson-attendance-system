@extends('remedial.layouts.master')
@section('title', 'Attendance')
@section('content')
    <div class="col-md-6 mx-auto">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New Teacher</h5>

            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{route('users.store')}}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="basic-default-fullname">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="basic-default-fullname" name="name" placeholder="John Doe" required />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="basic-default-company">Phone</label>
                        <input type="number" class="form-control @error('phone') is-invalid @enderror"
                            id="basic-default-company" name="phone" placeholder="0712345678" required />
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="basic-default-email">Email</label>
                        <div class="input-group input-group-merge">
                            <input type="email" id="basic-default-email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2"
                                required />
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                </form>



            </div>
        </div>
    </div>
@endsection
