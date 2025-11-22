@extends('layouts.app')

@section('title', 'Student Remedial Profile')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-6 space-y-8">

    <div class="bg-white shadow rounded-xl p-8 border border-gray-100">
        
        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">

            <div class="space-y-2">
                <h1 class="text-2xl font-bold text-gray-800">
                    {{ $student->name }}
                </h1>

                <div class="grid grid-cols-2 gap-x-8 gap-y-1 text-gray-700 text-sm">
                    <p><span class="font-semibold">ADM:</span> {{ $student->adm }}</p>
                    <p><span class="font-semibold">Grade:</span> {{ $student->grade->name ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Stream:</span> {{ $student->gradeStream->name ?? 'N/A' }}</p>
                    <p><span class="font-semibold">Status:</span> {{ $student->status }}</p>
                </div>
            </div>

            <div class="text-right space-y-1">
                <p class="text-lg font-semibold text-gray-700">
                    Total Paid:
                    <span class="text-green-700">
                        Ksh {{ number_format($totalPaid) }}
                    </span>
                </p>

                <p class="text-lg font-semibold"
                   style="color: {{ $balance > 0 ? 'red' : 'green' }};">
                    Balance:
                    Ksh {{ number_format($balance) }}
                </p>
            </div>

        </div>

        <div class="border-t mt-6 pt-4"></div>

        <p class="text-gray-500 text-sm italic">
            Remedial fee report generated on {{ now()->format('d M Y') }}.
        </p>

    </div>

    <div class="bg-white rounded-xl shadow p-8 border border-gray-100">

        <h2 class="text-xl font-bold text-gray-800 mb-6">
            Remedial Payment History
        </h2>

        @if ($payments->isEmpty())
            <p class="text-red-600 font-medium">No remedial payments found.</p>

        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-gray-700">
                            <th class="p-3 font-semibold text-sm">Date</th>
                            <th class="p-3 font-semibold text-sm text-right">Amount</th>
                            <th class="p-3 font-semibold text-sm">Type</th>
                            <th class="p-3 font-semibold text-sm">Mpesa No.</th>
                        </tr>
                    </thead>

                    <tbody class="text-sm text-gray-700">
                        @foreach ($payments as $index => $pay)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-3">{{ $pay->created_at->format('d M Y') }}</td>
                                <td class="p-3 text-right">Ksh {{ number_format($pay->amount) }}</td>
                                <td class="p-3 uppercase">{{ $pay->payment_type }}</td>
                                <td class="p-3">
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
