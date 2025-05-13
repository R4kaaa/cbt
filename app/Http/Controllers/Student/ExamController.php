<?php

namespace App\Http\Controllers\Student;

use Carbon\Carbon;
use App\Models\Grade;
use App\Models\Answer;
use App\Models\Question;
use App\Models\ExamGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    /**
     * confirmation
     *
     * @param  mixed $id
     * @return void
     */
    public function confirmation($id)
    {
        //get exam group
        $exam_group = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('id', $id)
            ->first();

        //get grade / nilai
        $grade = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        //return with inertia
        return inertia('Student/Exams/Confirmation', [
            'exam_group' => $exam_group,
            'grade' => $grade,
        ]);
    }

    /**
     * startExam
     *
     * @param  mixed $id
     * @return void
     */
    public function startExam($id)
    {
        //get exam group
        $exam_group = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('id', $id)
            ->first();

        //get grade / nilai
        $grade = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        //update start time di table grades
        $grade->start_time = Carbon::now();
        $grade->update();

        //cek apakah questions / soal ujian di random
        if ($exam_group->exam->random_question == 'Y') {

            //get questions / soal ujian
            $questions = Question::where('exam_id', $exam_group->exam->id)->inRandomOrder()->get();
        } else {

            //get questions / soal ujian
            $questions = Question::where('exam_id', $exam_group->exam->id)->get();
        }

        //define pilihan jawaban default
        $question_order = 1;

        foreach ($questions as $question) {

            //buat array jawaban / answer
            $options = [1, 2];
            if (!empty($question->option_3)) $options[] = 3;
            if (!empty($question->option_4)) $options[] = 4;
            if (!empty($question->option_5)) $options[] = 5;

            //acak jawaban / answer
            if ($exam_group->exam->random_answer == 'Y') {
                shuffle($options);
            }

            //cek apakah sudah ada data jawaban
            $answer = Answer::where('student_id', auth()->guard('student')->user()->id)
                ->where('exam_id', $exam_group->exam->id)
                ->where('exam_session_id', $exam_group->exam_session->id)
                ->where('question_id', $question->id)
                ->first();

            //jika sudah ada jawaban / answer
            if ($answer) {

                //update urutan question / soal
                $answer->question_order = $question_order;
                $answer->update();
            } else {

                //buat jawaban default baru
                Answer::create([
                    'exam_id'           => $exam_group->exam->id,
                    'exam_session_id'   => $exam_group->exam_session->id,
                    'question_id'       => $question->id,
                    'student_id'        => auth()->guard('student')->user()->id,
                    'question_order'    => $question_order,
                    'answer_order'      => implode(",", $options),
                    'answer'            => 0,
                    'selected_answers'  => json_encode([]), // Untuk multiple choice
                    'is_correct'        => 'N',
                    'score'             => 0 // Tambahkan field skor
                ]);
            }
            $question_order++;
        }

        //redirect ke ujian halaman 1
        return redirect()->route('student.exams.show', [
            'id'    => $exam_group->id,
            'page'  => 1
        ]);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @param  mixed $page
     * @return void
     */
    public function show($id, $page)
    {
        //get exam group
        $exam_group = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('id', $id)
            ->first();

        if (!$exam_group) {
            return redirect()->route('student.dashboard');
        }

        //get all questions
        $all_questions = Answer::with('question')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('exam_id', $exam_group->exam->id)
            ->orderBy('question_order', 'ASC')
            ->get();

        //count all question answered
        $question_answered = Answer::with('question')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('exam_id', $exam_group->exam->id)
            ->where(function ($query) {
                $query->where('answer', '!=', 0)
                    ->orWhereNotNull('selected_answers')
                    ->whereRaw("selected_answers != '[]'");
            })
            ->count();

        //get question active
        $question_active = Answer::with('question.exam')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('exam_id', $exam_group->exam->id)
            ->where('question_order', $page)
            ->first();

        //explode atau pecah jawaban
        if ($question_active) {
            $answer_order = explode(",", $question_active->answer_order);
        } else {
            $answer_order = [];
        }

        //get duration
        $duration = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        //return with inertia
        return inertia('Student/Exams/Show', [
            'id'                => (int) $id,
            'page'              => (int) $page,
            'exam_group'        => $exam_group,
            'all_questions'     => $all_questions,
            'question_answered' => $question_answered,
            'question_active'   => $question_active,
            'answer_order'      => $answer_order,
            'duration'          => $duration,
        ]);
    }

    /**
     * updateDuration
     *
     * @param  mixed $request
     * @param  mixed $grade_id
     * @return void
     */
    public function updateDuration(Request $request, $grade_id)
    {
        $grade = Grade::find($grade_id);
        $grade->duration = $request->duration;
        $grade->update();

        return response()->json([
            'success'  => true,
            'message' => 'Duration updated successfully.'
        ]);
    }

    /**
     * answerQuestion
     *
     * @param  mixed $request
     * @return void
     */
    public function answerQuestion(Request $request)
    {
        //update duration
        $grade = Grade::where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        $grade->duration = $request->duration;
        $grade->update();

        //get question
        $question = Question::find($request->question_id);

        //get answer record
        $answer = Answer::where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('question_id', $request->question_id)
            ->first();

        // Periksa tipe soal (single atau multiple) dan validasi jawaban
        if ($question->question_type === 'single') {
            // Untuk tipe soal single
            $is_correct = ($question->answer == $request->answer) ? 'Y' : 'N';
            $score = ($is_correct === 'Y') ? 1 : 0;

            // Update jawaban untuk tipe single
            if ($answer) {
                $answer->answer = $request->answer;
                $answer->is_correct = $is_correct;
                $answer->score = $score;
                $answer->update();
            }
        } else {
            // Untuk tipe soal multiple
            // Handle selected_answers data type properly
            $selected_answers = $request->selected_answers;
            // Check if selected_answers is a string that needs to be decoded
            if (is_string($selected_answers) && !empty($selected_answers)) {
                $selected_answers = json_decode($selected_answers, true);
            }

            // Make sure selected_answers is an array
            if (!is_array($selected_answers)) {
                $selected_answers = [];
            }

            $correct_answers = $question->answers;
            // Make sure correct_answers is an array
            if (!is_array($correct_answers)) {
                $correct_answers = [];
            }

            // Hitung berapa jawaban yang benar dan salah
            $user_correct_count = 0;

            // Cek jawaban yang benar dari user
            foreach ($selected_answers as $selected) {
                if (in_array($selected, $correct_answers)) {
                    $user_correct_count++;
                }
            }

            // Logika penilaian baru:
            // Jika semua benar dan tidak ada yang salah: nilai 1
            // Jika minimal satu jawaban benar: nilai 0.5
            // Jika tidak ada jawaban yang benar: nilai 0

            $total_correct_options = count($correct_answers);
            $user_wrong_count = count($selected_answers) - $user_correct_count;
            $missed_correct_options = $total_correct_options - $user_correct_count;

            if ($user_wrong_count == 0 && $missed_correct_options == 0) {
                // Semua jawaban benar dan tidak ada yang salah
                $score = 1;
                $is_correct = 'Y';
            } else if ($user_correct_count > 0) {
                // Minimal ada satu jawaban yang benar
                $score = 0.5;
                $is_correct = 'P'; // P untuk partial
            } else {
                // Tidak ada jawaban yang benar
                $score = 0;
                $is_correct = 'N';
            }

            // Update jawaban untuk tipe multiple
            if ($answer) {
                $answer->selected_answers = json_encode($selected_answers);
                $answer->is_correct = $is_correct;
                $answer->score = $score;
                $answer->update();
            }
        }

        return redirect()->back();
    }
    /**
     * endExam
     *
     * @param  mixed $request
     * @return void
     */
    public function endExam(Request $request)
    {
        // Get semua jawaban beserta soalnya
        $answers = Answer::with('question')
            ->where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->get();
        // dd($answers);
        // Hitung total skor
        $total_score = 0;
        $count_question = $answers->count();
        $count_correct_answer = 0; // Untuk menghitung pertanyaan yang 100% benar

        foreach ($answers as $answer) {
            $total_score += $answer->score;

            if ($answer->is_correct === 'Y') {
                $count_correct_answer++;
            } elseif ($answer->is_correct === 'P') {
                // Untuk jawaban parsial, kita bisa menghitung persentasenya
                // Tapi tidak menghitung sebagai jawaban penuh
            }
        }

        // Hitung nilai akhir (skor maksimal adalah jumlah soal)
        $grade_exam = round(($total_score / $count_question) * 100, 2);

        // Update nilai di table grades
        $grade = Grade::where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        $grade->end_time        = Carbon::now();
        $grade->total_correct   = $count_correct_answer;
        $grade->total_score     = $total_score;
        $grade->grade           = $grade_exam;
        $grade->update();

        // Persiapan pesan WhatsApp
        $student = auth()->guard('student')->user();
        $exam_group = ExamGroup::with('exam.lesson')->find($request->exam_group_id);
        $message = "*Hasil Ujian*\n\n"
            . "*Nama Siswa*: " . $student->name . "\n"
            . "*Mata Pelajaran*: " . $exam_group->exam->title . "\n"
            . "*Jumlah Soal*: " . $count_question . "\n"
            . "*Jawaban Benar*: " . $count_correct_answer . "\n"
            . "*Total Skor*: " . $total_score . " dari " . $count_question . "\n"
            . "*Nilai Akhir*: " . $grade_exam . "\n\n"
            . "Selamat atas hasil ujianmu!";

        // Kirim WhatsApp ke nomor siswa
        $receiver = $student->phone; // Nomor telepon siswa
        $this->sendWa($receiver, $message);

        //redirect hasil
        return redirect()->route('student.exams.resultExam', $request->exam_group_id);
    }

    // Metode sendWa tetap sama seperti sebelumnya
    public function sendWa($receiver, $message)
    {
        try {
            $token = "M9kpxFexDAbVG5CzcmLJ";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $receiver,
                    'message' => $message,
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $token
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
        } catch (\Throwable $th) {
            // Log error or handle exception
        }
    }

    /**
     * resultExam
     *
     * @param  mixed $id
     * @return void
     */
    public function resultExam($exam_group_id)
    {
        //get exam group
        $exam_group = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('id', $exam_group_id)
            ->first();

        //get grade / nilai
        $grade = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        //return with inertia
        return inertia('Student/Exams/Result', [
            'exam_group' => $exam_group,
            'grade'      => $grade,
        ]);
    }
}
