@extends('layouts.app')

@section('title', 'Remedial Payments Dashboard')

@section('content')
    <div class="max-w-6xl mx-auto py-4 text-xs">

        {{-- Alerts --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Current Academic Period --}}
        <div class="mb-2">
            @if ($currentYear && $currentTerm)
                <p class="text-red-600 text-xs font-semibold">
                    {{ $currentYear->year }} | {{ $currentTerm->name }}
                </p>
            @else
                <p class="text-red-600 text-xs font-semibold">
                    No current year or term found.
                </p>
            @endif
        </div>

        {{-- BUTTON + SEARCH INPUT ROW --}}
        <div class="flex items-end gap-6 mb-6">

            {{-- Capture New Payment --}}
            <a href="{{ route('remedial.payments.capture') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs shadow font-semibold tracking-wide">
                + New Payment
            </a>

            {{-- SEARCH STUDENT --}}
            <div 
                x-data="{
                    query: '',
                    results: [],
                    search() {
                        if (this.query.length < 2) { this.results = []; return; }
                        fetch('/remedial/search-student?q=' + this.query)
                            .then(res => res.json())
                            .then(data => this.results = data);
                    }
                }"
                class="relative"
            >

                <label class="text-[11px] font-semibold text-green-700 mb-1 block">
                    Search by ADM
                </label>

                <input type="text"
                    x-model="query"
                    @input="search()"
                    placeholder="Enter ADM number…"
                    class="border border-green-400 px-3 py-2 rounded-lg text-xs w-72 
                           shadow-sm focus:ring-2 focus:ring-green-300 focus:border-green-500
                           transition bg-green-50"
                />

                {{-- SEARCH RESULTS --}}
                <div x-show="results.length > 0"
                    class="absolute bg-white border w-72 shadow-lg z-20 mt-1 rounded text-xs max-h-60 overflow-auto">

                    <template x-for="item in results" :key="item.id">
                        <a :href="'/remedial/student/' + item.id"
                           class="block px-3 py-2 hover:bg-gray-100 border-b">
                            <span class="font-semibold" x-text="item.adm"></span> -
                            <span x-text="item.name"></span>
                            <span class="text-gray-600 block text-[10px] mt-0.5"
                                  x-text="'Grade ' + item.grade + ' ' + item.stream">
                            </span>
                        </a>
                    </template>
                </div>
            </div>

        </div>
        {{-- END SEARCH AND BUTTON ROW --}}

        {{-- Top Summary Card --}}
        <div class="bg-white shadow rounded p-4 mb-6 text-xs">
            <p class="font-semibold text-school-green">Total Collected This Active Term</p>
            <p class="text-lg font-bold">Ksh {{ number_format($activeTermTotal) }}</p>
        </div>

        {{-- Grades & Streams Analysis --}}
        <div class="space-y-12">
            @forelse($gradesAnalysis as $analysis)
                @php
                    $grade = $analysis['grade'];
                @endphp

                <div>
                    <h2 class="font-bold text-school-green mb-2">
                        {{ $grade->name }} 
                        @if ($analysis['supervisor'])
                            (Supervisor: {{ $analysis['supervisor'] }})
                        @endif
                    </h2>

                    @if ($analysis['total_collected'] == 0)
                        <p class="text-red-500 text-xs mb-2">No remedial payments recorded this term.</p>
                        <hr class="border-gray-300">
                        @continue
                    @endif

                    <div class="flex flex-wrap gap-4 mb-3">
                        <div>Amount Collected This Term:
                            <strong>Ksh {{ number_format($analysis['total_collected']) }}</strong>
                        </div>
                        <div>Total Paid:
                            <strong>{{ $analysis['students_paid'] }} out of {{ $analysis['total_students'] }}</strong>
                        </div>
                    </div>

                    <div class="mb-2">
                        <a href="{{ route('admin.pdf.remedial.grade', $grade->id) }}" target="_blank"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-1 rounded text-xs">
                            Generate Payment Slips PDF
                        </a>
                    </div>

                    {{-- Streams Table --}}
                    <table class="w-full bg-white shadow rounded overflow-hidden text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-1">Stream</th>
                                <th class="p-1">Class Teacher</th>
                                <th class="p-1 text-center">Paid / Total</th>
                                <th class="p-1 text-right">Collected</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($analysis['streams'] as $stream)
                                <tr>
                                    <td class="p-1">{{ $stream['stream_name'] }}</td>
                                    <td class="p-1">{{ $stream['teacher'] ?? '-' }}</td>
                                    <td class="p-1 text-center">
                                        {{ $stream['students_paid'] }} out of
                                        {{ $grade->streams->firstWhere('name', $stream['stream_name'])->students->count() }}
                                    </td>
                                    <td class="p-1 text-right">{{ number_format($stream['total_collected']) }}</td>
                                </tr>
                            @endforeach

                            <tr class="bg-gray-200 font-bold">
                                <td colspan="2" class="p-1 text-right">Total</td>
                                <td class="p-1 text-center"></td>
                                <td class="p-1 text-right">
                                    Ksh {{ number_format($analysis['streams_total_collected']) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr class="border-gray-300">
            @empty
                <p class="text-red-500">No grade data available.</p>
            @endforelse
        </div>

    </div>
@endsection
