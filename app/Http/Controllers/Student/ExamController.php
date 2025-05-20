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

        // If no answer exists, create one
        if (!$answer) {
            $answer = new Answer();
            $answer->exam_id = $request->exam_id;
            $answer->exam_session_id = $request->exam_session_id;
            $answer->student_id = auth()->guard('student')->user()->id;
            $answer->question_id = $request->question_id;
            // You might want to add question_order and answer_order if needed
        }

        // Process based on question type
        if ($question->question_type === 'single') {
            // For single choice type
            $is_correct = ($question->answer == $request->answer) ? 'Y' : 'N';
            $score = ($is_correct === 'Y') ? 1 : 0;

            // Update answer data
            $answer->answer = $request->answer;
            $answer->is_correct = $is_correct;
            $answer->score = $score;
        } else if ($question->question_type === 'multiple') {
            // For multiple choice type
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
                $correct_answers = json_decode($correct_answers, true);
                if (!is_array($correct_answers)) {
                    $correct_answers = [];
                }
            }

            // Count correct and wrong answers
            $user_correct_count = 0;

            // Check correct answers from user
            foreach ($selected_answers as $selected) {
                if (in_array($selected, $correct_answers)) {
                    $user_correct_count++;
                }
            }

            $total_correct_options = count($correct_answers);
            $user_wrong_count = count($selected_answers) - $user_correct_count;
            $missed_correct_options = $total_correct_options - $user_correct_count;

            if ($user_wrong_count == 0 && $missed_correct_options == 0) {
                // All answers are correct and none are missing
                $score = 1;
                $is_correct = 'Y';
            } else if ($user_correct_count > 0) {
                // At least one answer is correct
                $score = 0.5;
                $is_correct = 'P'; // P for partial
            } else {
                // No correct answers
                $score = 0;
                $is_correct = 'N';
            }

            // Update answer data
            $answer->selected_answers = json_encode($selected_answers);
            $answer->is_correct = $is_correct;
            $answer->score = $score;
        } else if ($question->question_type === 'essay') {
            // For essay type questions
            $student_essay = $request->essay_answer;
            $correct_essay = $question->essay_answer;

            // Use our essay grading service
            $essayGradingService = new \App\Services\EssayGradingService();
            $gradingResult = $essayGradingService->gradeEssay($student_essay, $correct_essay);

            // Update answer data
            $answer->essay_answer = $student_essay;
            $answer->is_correct = $gradingResult['is_correct'];
            $answer->score = $gradingResult['score'];
            $answer->similarity_score = $gradingResult['similarity'];

            // Optionally store additional metrics
            // $answer->grading_metrics = json_encode($gradingResult['metrics']);
        }

        // Save the answer
        $answer->save();

        return redirect()->back();
    }
    public function endExam(Request $request)
    {
        // Get all answers with their questions
        $answers = Answer::with('question')
            ->where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->get();

        // Hitung total skor
        $total_score = 0;
        $count_question = $answers->count();
        $count_correct_answer = 0;       // For 100% correct answers
        $count_partial_correct = 0;      // For partially correct answers (multiple choice/essay)

        foreach ($answers as $answer) {
            // Add score to total
            $total_score += $answer->score;

            // Count fully correct answers
            if ($answer->is_correct === 'Y') {
                $count_correct_answer++;
            }
            // Count partially correct answers
            elseif ($answer->is_correct === 'P') {
                $count_partial_correct++;
            }
        }

        // Calculate final grade (score out of 100)
        $grade_exam = ($count_question > 0) ? round(($total_score / $count_question) * 100, 2) : 0;

        // Update grade in the database
        $grade = Grade::where('exam_id', $request->exam_id)
            ->where('exam_session_id', $request->exam_session_id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

        $grade->end_time        = Carbon::now();
        $grade->total_correct   = $count_correct_answer;
        $grade->total_partial   = $count_partial_correct;  // Optional: add this column to grades table
        $grade->total_score     = $total_score;
        $grade->grade           = $grade_exam;
        $grade->update();

        // Prepare WhatsApp message
        $student = auth()->guard('student')->user();
        $exam_group = ExamGroup::with('exam.lesson')->find($request->exam_group_id);

        // Enhanced message with partial correct info
        $message = "*Hasil Ujian*\n\n"
            . "*Nama Siswa*: " . $student->name . "\n"
            . "*Mata Pelajaran*: " . $exam_group->exam->title . "\n"
            . "*Jumlah Soal*: " . $count_question . "\n"
            . "*Jawaban Benar*: " . $count_correct_answer . "\n";

        // Add partial correct info if there are any
        if ($count_partial_correct > 0) {
            $message .= "*Jawaban Sebagian Benar*: " . $count_partial_correct . "\n";
        }

        $message .= "*Total Skor*: " . $total_score . " dari " . $count_question . "\n"
            . "*Nilai Akhir*: " . $grade_exam . "\n\n"
            . "Selamat atas hasil ujianmu!";

        // Send WhatsApp message
        $receiver = $student->phone;
        $this->sendWa($receiver, $message);

        // Redirect to result page
        return redirect()->route('student.exams.resultExam', $request->exam_group_id);
    }

    // Metode sendWa tetap sama seperti sebelumnya
    public function sendWa($receiver, $message)
    {
        try {
            $token = "gPJWPKThyVDTpJfwdMFW";
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
