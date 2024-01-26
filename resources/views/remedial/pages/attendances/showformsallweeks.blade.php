@extends('remedial.layouts.master')

@section('title', 'Forms')

@section('content')
<div class="col-md-8">
    <div class="card">
        @can('super')
        <!-- Super admin specific content -->
        @endcan

        <div class="table-responsive text-nowrap">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>

                        <th>Form</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($forms as $form)
                    <tr onclick="window.location='{{ route('form.attendance.show', $form->id) }}';" style="cursor: pointer;">

                        <td>{{ $form->name }}</td>
                        <td>{{ $form->attendances->count() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
