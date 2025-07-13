<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
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
            'classroom_id'  => (int) $row['classroom_id'],
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
            'nik' => 'size:16|unique:students,nisn',
        ];
    }
}
