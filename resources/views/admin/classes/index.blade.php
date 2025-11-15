@extends('layouts.app')

@section('title', 'Manage Classes')

@section('content')
<h1 class="text-2xl font-bold text-school-green mb-4">Curricula</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @foreach($curricula as $curriculum)
        <a href="{{ route('classes.show', $curriculum->id) }}" class="bg-card shadow rounded-lg p-6 hover:bg-green-100 transition">
            <h2 class="text-xl font-semibold">{{ $curriculum->name }}</h2>
        </a>
    @endforeach
</div>
@endsection
