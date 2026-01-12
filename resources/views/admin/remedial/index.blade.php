@extends('layouts.app')

@section('title', 'Remedial Payments')

@section('content')
    <div class="max-w-3xl mx-auto py-6">

        {{-- Page Header --}}
        <h1 class="text-2xl font-bold text-school-green mb-6">Remedial Payments</h1>

        {{-- Remedial Payments --}}
        <div>
            <a href="{{ route('payments.index') }}"
               class="block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg shadow transition text-sm">
                Remedial Payments
            </a>
        </div>

    </div>
@endsection
