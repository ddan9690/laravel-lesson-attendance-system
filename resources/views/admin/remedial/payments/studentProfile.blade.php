@extends('layouts.app')

@section('title', 'Student Remedial Profile')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4">

    {{-- Student Header Card --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center">
            {{-- Student Info --}}
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl font-bold text-school-green">
                    {{ $student->name }} <span class="text-gray-500 text-base">ADM: {{ $student->adm }}</span>
                </h1>
                <p class="mt-2 text-gray-700">
                    <span class="font-semibold"></span> {{ $student->grade->name ?? 'N/A' }} 
                    <span class="ml-4 font-semibold"></span> {{ $student->gradeStream->name ?? 'N/A' }}
                </p>
            </div>

            {{-- Payments Summary --}}
            <div class="text-left md:text-right">
                <p class="text-green-700 font-semibold text-lg">
                    Total Paid: Ksh {{ number_format($totalPaid) }}
                </p>
                <p class="font-semibold text-lg" style="color: {{ $balance > 0 ? 'red' : 'green' }};">
                    Balance: Ksh {{ number_format($balance) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Payments Table --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="font-bold text-school-green text-xl mb-4">Remedial Payments</h2>

        @if ($payments->isEmpty())
            <p class="text-red-600 font-medium">No remedial payments recorded.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            {{-- <th class="p-3 border-b text-center">#</th> --}}
                            <th class="p-3 border-b">Date</th>
                            <th class="p-3 border-b text-right">Amount</th>
                            <th class="p-3 border-b">Type</th>
                            <th class="p-3 border-b">Mpesa No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $index => $pay)
                            <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : '' }}">
                                {{-- <td class="p-3 border-b text-center">{{ $index + 1 }}</td> --}}
                                <td class="p-3 border-b">{{ $pay->created_at->format('M Y') }}</td>
                                <td class="p-3 border-b text-right">{{ number_format($pay->amount) }}</td>
                                <td class="p-3 border-b uppercase">{{ $pay->payment_type }}</td>
                                <td class="p-3 border-b">
                                    @if($pay->payment_type === 'mpesa')
                                        {{ $pay->mpesa_transaction_number ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
