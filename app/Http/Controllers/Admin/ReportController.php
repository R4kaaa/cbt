<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use App\Exports\GradesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all exams
        $exams = Exam::with('lesson', 'classroom')->get();

        return inertia('Admin/Reports/Index', [
            'exams'         => $exams,
            'grades'        => [],
            'showAlert'     => false,
            'alertMessage'  => ''
        ]);
    }

    /**
     * filter
     *
     * @param  mixed $request
     * @return void
     */
    public function filter(Request $request)
    {
        $this->validate($request, [
            'exam_id'       => 'required',
        ]);

        //get all exams
        $exams = Exam::with('lesson', 'classroom')->get();

        //get exam
        $exam = Exam::with('lesson', 'classroom')
            ->where('id', $request->exam_id)
            ->first();

        $grades = [];
        $showAlert = false;
        $alertMessage = '';

        if ($exam) {

            //get exam session
            $exam_session = ExamSession::where('exam_id', $exam->id)->first();

            if ($exam_session) {
                //get grades / nilai
                $grades = Grade::with('student', 'exam.classroom', 'exam.lesson', 'exam_session')
                    ->where('exam_id', $exam->id)
                    ->where('exam_session_id', $exam_session->id)
                    ->get();

                // Jika tidak ada grades ditemukan
                if ($grades->isEmpty()) {
                    $showAlert = true;
                    $alertMessage = 'Ujian "' . $exam->title . '" belum dikerjakan oleh siswa atau belum ada nilai yang tersedia.';
                }
            } else {
                // Jika exam session tidak ditemukan
                $showAlert = true;
                $alertMessage = 'Sesi ujian untuk "' . $exam->title . '" belum dibuat atau belum tersedia.';
            }
        } else {
            // Jika exam tidak ditemukan
            $showAlert = true;
            $alertMessage = 'Ujian tidak ditemukan.';
        }

        return inertia('Admin/Reports/Index', [
            'exams'         => $exams,
            'grades'        => $grades,
            'showAlert'     => $showAlert,
            'alertMessage'  => $alertMessage
        ]);
    }

    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
    public function export(Request $request)
    {
        // Validasi exam_id
        if (!$request->exam_id) {
            return redirect()->back()->with('error', 'Pilih ujian terlebih dahulu');
        }

        //get exam
        $exam = Exam::with('lesson', 'classroom')
            ->where('id', $request->exam_id)
            ->first();

        if (!$exam) {
            return redirect()->back()->with('error', 'Ujian tidak ditemukan');
        }

        //get exam session
        $exam_session = ExamSession::where('exam_id', $exam->id)->first();

        if (!$exam_session) {
            return redirect()->back()->with('error', 'Sesi ujian tidak ditemukan');
        }

        //get grades / nilai
        $grades = Grade::with('student', 'exam.classroom', 'exam.lesson', 'exam_session')
            ->where('exam_id', $exam->id)
            ->where('exam_session_id', $exam_session->id)
            ->get();

        // Cek jika tidak ada grades
        if ($grades->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data nilai untuk diexport. Ujian belum dikerjakan oleh siswa.');
        }

        return Excel::download(new GradesExport($grades), 'grade : ' . $exam->title . ' — ' . $exam->lesson->title . ' — ' . Carbon::now() . '.xlsx');
    }
}
