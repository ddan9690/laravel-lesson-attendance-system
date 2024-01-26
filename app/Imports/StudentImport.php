<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    protected $clazzId;
    protected $streamId;

    public function __construct($clazzId, $streamId)
    {
        $this->clazzId = $clazzId;
        $this->streamId = $streamId;
    }

    public function model(array $row)
    {
        // Assuming 'adm' and 'name' are the column names in the Excel file
        return new Student([
            'adm' => $row['adm'],
            'name' => $row['name'],
            'clazz_id' => $this->clazzId,
            'stream_id' => $this->streamId,
        ]);
    }
}
