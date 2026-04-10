<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function __construct(private readonly int $classroomId)
    {
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Student([
            'nisn'          => (int) $row['nik'],
            'name'          => $row['name'],
            'email'         => $row['email'],
            'phone'         => $row['phone'],
            'password'      => $row['password'],
            'gender'        => $row['gender'],
            'classroom_id'  => $this->classroomId,
        ]);
    }

    /**
     * rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nik' => 'required|size:16|unique:students,nisn',
            '*.name' => 'required|string|max:255',
            '*.email' => 'nullable|email|unique:students,email',
            '*.phone' => 'nullable|string|max:20|unique:students,phone',
            '*.password' => 'required|string',
            '*.gender' => ['required', Rule::in(['L', 'P'])],
        ];
    }
}
