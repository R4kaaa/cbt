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

        // Get questions berdasarkan urutan tipe: single -> multiple -> essay
        $questions_single = Question::where('exam_id', $exam_group->exam->id)
            ->where('question_type', 'single');
        $questions_multiple = Question::where('exam_id', $exam_group->exam->id)
            ->where('question_type', 'multiple');
        $questions_essay = Question::where('exam_id', $exam_group->exam->id)
            ->where('question_type', 'essay');

        // Terapkan random jika diaktifkan, tapi per tipe soal
        if ($exam_group->exam->random_question == 'Y') {
            $questions_single = $questions_single->inRandomOrder();
            $questions_multiple = $questions_multiple->inRandomOrder();
            $questions_essay = $questions_essay->inRandomOrder();
        }

        // Gabungkan semua soal dengan urutan: single -> multiple -> essay
        $questions = collect()
            ->merge($questions_single->get())
            ->merge($questions_multiple->get())
            ->merge($questions_essay->get());

        //define pilihan jawaban default
        $question_order = 1;

        foreach ($questions as $question) {

            //buat array jawaban / answer
            $options = [1, 2];
            if (!empty($question->option_3)) $options[] = 3;
            if (!empty($question->option_4)) $options[] = 4;
            if (!empty($question->option_5)) $options[] = 5;

            //acak jawaban / answer (hanya untuk single dan multiple choice)
            if ($exam_group->exam->random_answer == 'Y' && in_array($question->question_type, ['single', 'multiple'])) {
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
                    'answer_order'      => $question->question_type === 'essay' ? '' : implode(",", $options),
                    'answer'            => 0,
                    'selected_answers'  => json_encode([]), // Untuk multiple choice
                    'essay_answer'      => '', // Untuk essay
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

    public function show($id, $page)
    {
        $exam_group = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('id', $id)
            ->first();

        if (!$exam_group) {
            return redirect()->route('student.dashboard');
        }

        $all_questions = Answer::with(['question' => function ($query) {
            $query->select('id', 'exam_id', 'question', 'question_type', 'media_type', 'question_image', 'audio_file', 'option_1', 'option_2', 'option_3', 'option_4', 'option_5');
        }])
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->orderBy('question_order', 'ASC')
            ->get();

        $question_answered = Answer::with('question')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('question', function ($qq) {
                        $qq->where('question_type', 'single');
                    })->where('answer', '!=', 0);
                })->orWhere(function ($q) {
                    $q->whereHas('question', function ($qq) {
                        $qq->where('question_type', 'multiple');
                    })->whereNotNull('selected_answers')
                        ->whereRaw("selected_answers != '[]'");
                })->orWhere(function ($q) {
                    $q->whereHas('question', function ($qq) {
                        $qq->where('question_type', 'essay');
                    })->whereNotNull('essay_answer')
                        ->where('essay_answer', '!=', '');
                });
            })
            ->count();

        $question_active = Answer::with(['question' => function ($query) {
            $query->with('exam');
        }])
            ->where('student_id', auth()->guard('student')->user()->id)
            ->where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('question_order', $page)
            ->first();

        if ($question_active && in_array($question_active->question->question_type, ['single', 'multiple'])) {
            $answer_order = explode(",", $question_active->answer_order);
        } else {
            $answer_order = [];
        }

        $duration = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', auth()->guard('student')->user()->id)
            ->first();

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
        $grade_exam = ($count_question > 0) ? round(($total_score / $count_question) * 250, 2) : 0;

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

        // Get KKM from lesson
        $kkm = 200;

        // Enhanced message with partial correct info
        $message = "*Hasil Ujian*\n\n"
            . "*Nama Siswa*: " . $student->name . "\n"
            . "*Materi*: " . $exam_group->exam->title . "\n"
            . "*Jumlah Soal*: " . $count_question . "\n"
            . "*Jawaban Benar*: " . $count_correct_answer . "\n";

        // Add partial correct info if there are any
        if ($count_partial_correct > 0) {
            $message .= "*Jawaban Sebagian Benar*: " . $count_partial_correct . "\n";
        }

        $message .= "*Total Skor*: " . $total_score . " dari " . $count_question . "\n"
            . "*Nilai Akhir*: " . $grade_exam . "\n"
            . "*KKM*: " . $kkm . "\n\n";

        // Check if grade meets KKM and add appropriate message
        if ($grade_exam >= $kkm) {
            $message .= "🎉 *Selamat!* 🎉\n"
                . "Kamu berhasil mencapai KKM dengan nilai yang sangat baik! "
                . "Pertahankan semangat belajarmu dan terus tingkatkan prestasi. "
                . "Kerja keras dan dedikasimu membuahkan hasil yang membanggakan!";
        } else {
            $message .= "💪 *Jangan Menyerah!* 💪\n"
                . "Nilai kamu belum mencapai KKM, tapi ini bukan akhir dari segalanya. "
                . "Setiap kegagalan adalah kesempatan untuk belajar dan berkembang. "
                . "Pelajari kembali materi yang kurang dipahami, berlatih lebih giat, "
                . "dan yakinlah bahwa usaha yang konsisten akan membawa hasil yang memuaskan. "
                . "Semangat terus, kesuksesan menanti di depan! 🌟";
        }

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
    public function history($student_id = null)
    {
        if (!$student_id) {
            $student_id = auth()->guard('student')->user()->id;
        }


        $exam_history = ExamGroup::with([
            'exam.lesson',
            'exam_session',
            'student.classroom',
            'grade' => function ($query) use ($student_id) {
                $query->where('student_id', $student_id);
            }
        ])
            ->where('student_id', $student_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($exam_group) use ($student_id) {

                $grade = Grade::where('exam_id', $exam_group->exam->id)
                    ->where('exam_session_id', $exam_group->exam_session->id)
                    ->where('student_id', $student_id)
                    ->first();


                $answers = Answer::with('question')
                    ->where('exam_id', $exam_group->exam->id)
                    ->where('exam_session_id', $exam_group->exam_session->id)
                    ->where('student_id', $student_id)
                    ->get();


                $stats = [
                    'total_questions' => $answers->count(),
                    'single_choice' => [
                        'total' => $answers->where('question.question_type', 'single')->count(),
                        'correct' => $answers->where('question.question_type', 'single')->where('is_correct', 'Y')->count(),
                        'wrong' => $answers->where('question.question_type', 'single')->where('is_correct', 'N')->count(),
                    ],
                    'multiple_choice' => [
                        'total' => $answers->where('question.question_type', 'multiple')->count(),
                        'correct' => $answers->where('question.question_type', 'multiple')->where('is_correct', 'Y')->count(),
                        'partial' => $answers->where('question.question_type', 'multiple')->where('is_correct', 'P')->count(),
                        'wrong' => $answers->where('question.question_type', 'multiple')->where('is_correct', 'N')->count(),
                    ],
                    'essay' => [
                        'total' => $answers->where('question.question_type', 'essay')->count(),
                        'correct' => $answers->where('question.question_type', 'essay')->where('is_correct', 'Y')->count(),
                        'partial' => $answers->where('question.question_type', 'essay')->where('is_correct', 'P')->count(),
                        'wrong' => $answers->where('question.question_type', 'essay')->where('is_correct', 'N')->count(),
                    ]
                ];


                $status = 'not_started';
                if ($grade) {
                    if ($grade->end_time) {
                        $status = 'completed';
                    } else if ($grade->start_time) {
                        $status = 'in_progress';
                    }
                }


                $duration_taken = null;
                if ($grade && $grade->start_time && $grade->end_time) {
                    $start = Carbon::parse($grade->start_time);
                    $end = Carbon::parse($grade->end_time);
                    $duration_taken = $end->diffInMinutes($start);
                }

                $passed = false;
                if ($grade && $grade->grade >= $exam_group->exam->lesson->kkm) {
                    $passed = true;
                }

                return [
                    'exam_group' => $exam_group,
                    'grade' => $grade,
                    'stats' => $stats,
                    'status' => $status,
                    'duration_taken' => $duration_taken,
                    'passed' => $passed,
                    'exam_date' => $exam_group->created_at->format('d/m/Y H:i'),
                ];
            });
        $overall_stats = [
            'total_exams' => $exam_history->count(),
            'completed_exams' => $exam_history->where('status', 'completed')->count(),
            'passed_exams' => $exam_history->where('passed', true)->count(),
            'average_score' => $exam_history->where('status', 'completed')->avg('grade.grade') ?? 0,
            'highest_score' => $exam_history->where('status', 'completed')->max('grade.grade') ?? 0,
            'lowest_score' => $exam_history->where('status', 'completed')->min('grade.grade') ?? 0,
        ];

        $student = auth()->guard('student')->user();

        return inertia('Student/Exams/History', [
            'student' => $student,
            'exam_history' => $exam_history,
            'overall_stats' => $overall_stats,
        ]);
    }

    public function historyDetail($exam_group_id)
    {
        $student_id = auth()->guard('student')->user()->id;
        $exam_group = ExamGroup::with([
            'exam.lesson',
            'exam_session',
            'student.classroom'
        ])
            ->where('id', $exam_group_id)
            ->where('student_id', $student_id)
            ->first();

        if (!$exam_group) {
            return redirect()->route('student.exams.history');
        }
        $grade = Grade::where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', $student_id)
            ->first();
        $answers = Answer::with(['question' => function ($query) {
            $query->select('id', 'exam_id', 'question', 'question_type', 'media_type', 'question_image', 'audio_file', 'option_1', 'option_2', 'option_3', 'option_4', 'option_5', 'answer', 'answers', 'essay_answer');
        }])
            ->where('exam_id', $exam_group->exam->id)
            ->where('exam_session_id', $exam_group->exam_session->id)
            ->where('student_id', $student_id)
            ->orderBy('question_order', 'ASC')
            ->get()
            ->map(function ($answer) {
                if ($answer->question->question_type === 'multiple') {
                    $answer->selected_answers_decoded = json_decode($answer->selected_answers, true) ?? [];
                    $answer->correct_answers = is_array($answer->question->answers) ?
                        $answer->question->answers :
                        json_decode($answer->question->answers, true) ?? [];
                }

                if (in_array($answer->question->question_type, ['single', 'multiple'])) {
                    $answer->answer_order_array = explode(',', $answer->answer_order);
                }

                return $answer;
            });

        return inertia('Student/Exams/HistoryDetail', [
            'exam_group' => $exam_group,
            'grade' => $grade,
            'answers' => $answers,
        ]);
    }
}
