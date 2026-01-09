<div class="mb-6">
    <h1 class="text-3xl font-bold text-green-800">
        {{ $academicContext['academicYear']->year ?? 'No Active Academic Year' }}
        <span class="text-lg font-semibold text-gray-700 ml-3">
            {{ $academicContext['academicTerm']->name ?? 'No Active Term' }}
            @if($academicContext['academicWeek'])
                <span class="text-blue-700">Week {{ $academicContext['academicWeek']->name }}</span>
            @else
                 <span class="text-red-600">No Active Week</span>
            @endif
        </span>
    </h1>
</div>
