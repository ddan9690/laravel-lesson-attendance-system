<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\GradeStream;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    private $streamId;
    private $gradeId;
    private $academicYearId;
    private $termId;
    private $addedBy;

    public function __construct($streamId, $academicYearId, $termId, $addedBy)
    {
        $this->streamId = $streamId;
        $this->academicYearId = $academicYearId;
        $this->termId = $termId;
        $this->addedBy = $addedBy;

        // Get the grade_id from the stream to prevent null constraint errors
        $stream = GradeStream::findOrFail($streamId);
        $this->gradeId = $stream->grade_id;
    }

    public function model(array $row)
    {
        // Normalize headings: lowercase and trim spaces
        $row = collect($row)->mapWithKeys(function($value, $key) {
            return [strtolower(trim($key)) => $value];
        })->toArray();

        // Skip rows missing required fields
        if (empty($row['adm']) || empty($row['name'])) {
            return null;
        }

        return new Student([
            'name' => $row['name'],
            'adm' => $row['adm'],
            'phone' => $row['phone'] ?? null,
            'grade_stream_id' => $this->streamId,
            'grade_id' => $this->gradeId, // guaranteed non-null
            'status' => 'active',
            'joined_academic_year_id' => $this->academicYearId,
            'joined_term_id' => $this->termId,
            'added_by' => $this->addedBy,
            'slug' => Str::slug($row['name']),
        ]);
    }
}
