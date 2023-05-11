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

                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf
                            <div>
                                <label for="smallSelect" class="form-label">Teacher</label>
                                <select id="selectteacher" name="teacher" required class="form-select selectteacher form-select-sm" >
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
                                        <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
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
                                        <option value="{{ $week->id }}">{{ $week->week_number }}</option>
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


