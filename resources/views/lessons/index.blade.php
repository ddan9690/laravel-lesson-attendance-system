@extends('layouts.app')

@section('title', 'Remedial Lesson Management')

@section('content')
<div class="max-w-4xl mx-auto py-12 text-center">

    <h1 class="text-3xl font-extrabold text-school-green mb-8">
        Lesson Management
    </h1>

    <div class="mb-8 flex justify-center flex-wrap gap-4">
        @foreach($curricula as $curriculum)
            <a href="" 
               class="px-6 py-3 bg-school-green text-white rounded-lg hover:bg-green-700 transition">
                {{ $curriculum->name }}
            </a>
        @endforeach
    </div>

</div>
@endsection
