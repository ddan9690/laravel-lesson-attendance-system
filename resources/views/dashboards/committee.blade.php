@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-6">
    <div class="bg-white text-gray-800 rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold">
            Logged in as: {{ auth()->user()->name }} – 
            <span class="font-bold text-green-700">{{ auth()->user()->getRoleNames()->first() }}</span>
        </h2>
    </div>
</div>
@endsection
