@extends('layouts.app')

@section('title', 'Remedial Management')

@section('content')
    <div class="max-w-3xl mx-auto py-6">

        {{-- Page Header --}}
        <h1 class="text-2xl font-bold text-school-green mb-6">Remedial Management</h1>

        {{-- Buttons to Lessons or Payments --}}
        <div class="flex gap-4">

            {{-- Remedial Lessons (only for those who can capture lessons) --}}
            @can('lesson_capture')
            <a href="{{ route('lessons.index') }}"
               class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg shadow transition text-sm">
                Remedial Lessons
            </a>
        @endcan


            <a href="{{ route('payments.index') }}"
                class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg shadow transition text-sm">
                Remedial Payments
            </a>


        </div>

    </div>
@endsection
