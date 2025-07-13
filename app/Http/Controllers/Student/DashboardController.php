<?php

namespace App\Http\Controllers\Student;

use App\Models\Grade;
use App\Models\ExamGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // Ambil exam_groups dengan relasi yang diperlukan
        $exam_groups = ExamGroup::with([
            'exam.lesson',
            'exam_session',
            'student.classroom'
        ])
            ->where('student_id', auth()->guard('student')->user()->id)
            ->get();

        $data = [];

        foreach ($exam_groups as $exam_group) {
            // Cek apakah sudah ada grade untuk ujian ini
            $grade = Grade::where('exam_id', $exam_group->exam_id)
                ->where('student_id', auth()->guard('student')->user()->id)
                ->where('exam_session_id', $exam_group->exam_session_id) // Tambahkan kondisi session
                ->first();

            // Jika belum ada grade, buat grade baru
            if ($grade == null) {
                $grade = new Grade();
                $grade->exam_id         = $exam_group->exam_id;
                $grade->exam_session_id = $exam_group->exam_session_id;
                $grade->student_id      = auth()->guard('student')->user()->id;
                $grade->duration        = $exam_group->exam->duration * 60000;
                $grade->total_correct   = 0;
                $grade->grade           = 0;
                $grade->save();
            }

            // Struktur data yang akan dikirim ke frontend
            $data[] = [
                'exam_group' => $exam_group,
                'grade'      => $grade
            ];
        }

        return inertia('Student/Dashboard/Index', [
            'exam_groups' => $data,
        ]);
    }
}
