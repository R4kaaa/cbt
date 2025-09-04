<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Imports\QuestionsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{

    public function index()
    {
        $exams = Exam::when(request()->q, function ($exams) {
            $exams = $exams->where('title', 'like', '%' . request()->q . '%');
        })->with('lesson', 'classroom', 'questions')->latest()->paginate(5);
        $exams->appends(['q' => request()->q]);

        return inertia('Admin/Exams/Index', [
            'exams' => $exams,
        ]);
    }
    public function create()
    {
        $lessons = Lesson::all();
        $classrooms = Classroom::all();

        return inertia('Admin/Exams/Create', [
            'lessons' => $lessons,
            'classrooms' => $classrooms,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required',
            'lesson_id'         => 'required|integer',
            'classroom_id'      => 'required|integer',
            'duration'          => 'required|integer',
            'description'       => 'required',
            'random_question'   => 'required',
            'random_answer'     => 'required',
            'show_answer'       => 'required',
        ]);

        Exam::create([
            'title'             => $request->title,
            'lesson_id'         => $request->lesson_id,
            'classroom_id'      => $request->classroom_id,
            'duration'          => $request->duration,
            'description'       => $request->description,
            'random_question'   => $request->random_question,
            'random_answer'     => $request->random_answer,
            'show_answer'       => $request->show_answer,
        ]);

        return redirect()->route('admin.exams.index');
    }

    public function show($id)
    {
        $exam = Exam::with('lesson', 'classroom')->findOrFail($id);
        $exam->setRelation('questions', $exam->questions()->paginate(5));
        return inertia('Admin/Exams/Show', [
            'exam' => $exam,
        ]);
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        $lessons = Lesson::all();
        $classrooms = Classroom::all();
        return inertia('Admin/Exams/Edit', [
            'exam' => $exam,
            'lessons' => $lessons,
            'classrooms' => $classrooms,
        ]);
    }
    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title'             => 'required',
            'lesson_id'         => 'required|integer',
            'classroom_id'      => 'required|integer',
            'duration'          => 'required|integer',
            'description'       => 'required',
            'random_question'   => 'required',
            'random_answer'     => 'required',
            'show_answer'       => 'required',
        ]);
        $exam->update([
            'title'             => $request->title,
            'lesson_id'         => $request->lesson_id,
            'classroom_id'      => $request->classroom_id,
            'duration'          => $request->duration,
            'description'       => $request->description,
            'random_question'   => $request->random_question,
            'random_answer'     => $request->random_answer,
            'show_answer'       => $request->show_answer,
        ]);
        return redirect()->route('admin.exams.index');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();
        return redirect()->route('admin.exams.index');
    }

    public function createQuestion(Exam $exam)
    {
        return inertia('Admin/Questions/Create', [
            'exam' => $exam,
        ]);
    }

    public function updateQuestion(Request $request, Exam $exam, Question $question)
    {
        $validatedData = $request->validate([
            'question'          => 'required',
            'option_1'          => 'nullable|required_if:question_type,single,multiple',
            'option_2'          => 'nullable|required_if:question_type,single,multiple',
            'option_3'          => 'nullable|required_if:question_type,single,multiple',
            'option_4'          => 'nullable|required_if:question_type,single,multiple',
            'option_5'          => 'nullable|required_if:question_type,single,multiple',
            'question_type'     => 'required|in:single,multiple,essay',
        ]);

        $questionImage = $question->question_image;
        $audioFile = $question->audio_file;

        if ($request->media_type === 'image') {
            if ($request->hasFile('question_image')) {
                // Delete old image if exists
                if ($question->question_image && Storage::disk('public_html')->exists('questions/' . $question->question_image)) {
                    Storage::disk('public_html')->delete('questions/' . $question->question_image);
                }

                // Generate unique filename
                $image = $request->file('question_image');
                $examTitle = str_replace(' ', '_', strtolower($exam->title));
                $timestamp = now()->format('YmdHis'); // Format: YearMonthDayHourMinuteSecond
                $extension = $image->getClientOriginalExtension();
                $questionImage = "{$examTitle}_{$timestamp}.{$extension}";

                // Save directly to public_html/storage/questions
                $image->storeAs('questions', $questionImage, 'public_html');
            }
            $audioFile = null;
        } else if ($request->media_type === 'audio') {
            if ($request->hasFile('audio_file')) {
                // Delete old audio if exists
                if ($question->audio_file && Storage::disk('public_html')->exists('questions/' . $question->audio_file)) {
                    Storage::disk('public_html')->delete('questions/' . $question->audio_file);
                }

                // Generate unique filename
                $audio = $request->file('audio_file');
                $examTitle = str_replace(' ', '_', strtolower($exam->title));
                $timestamp = now()->format('YmdHis'); // Format: YearMonthDayHourMinuteSecond
                $extension = $audio->getClientOriginalExtension();
                $audioFile = "{$examTitle}_{$timestamp}.{$extension}";

                // Save directly to public_html/storage/questions
                $audio->storeAs('questions', $audioFile, 'public_html');
            }
            $questionImage = null;
        } else if ($request->media_type === 'none') {
            // Delete existing media files if switching to no media
            if ($question->question_image && Storage::disk('public_html')->exists('questions/' . $question->question_image)) {
                Storage::disk('public_html')->delete('questions/' . $question->question_image);
            }
            if ($question->audio_file && Storage::disk('public_html')->exists('questions/' . $question->audio_file)) {
                Storage::disk('public_html')->delete('questions/' . $question->audio_file);
            }

            $questionImage = null;
            $audioFile = null;
        }


        if ($request->question_type === 'single') {
            $question->update([
                'question'          => $request->question,
                'question_image'    => $questionImage,
                'audio_file'        => $audioFile,
                'option_1'          => $request->option_1,
                'option_2'          => $request->option_2,
                'option_3'          => $request->option_3,
                'option_4'          => $request->option_4,
                'option_5'          => $request->option_5,
                'question_type'     => 'single',
                'answer'            => $request->answer,
                'answers'           => null,
                'essay_answer'      => null,
                'media_type'        => $request->media_type,
            ]);
        } else if ($request->question_type === 'multiple') {
            $question->update([
                'question'          => $request->question,
                'question_image'    => $questionImage,
                'audio_file'        => $audioFile,
                'option_1'          => $request->option_1,
                'option_2'          => $request->option_2,
                'option_3'          => $request->option_3,
                'option_4'          => $request->option_4,
                'option_5'          => $request->option_5,
                'question_type'     => 'multiple',
                'answer'            => null,
                'answers'           => $request->answers,
                'essay_answer'      => null,
                'media_type'        => $request->media_type,
            ]);
        } else { // essay
            $question->update([
                'question'          => $request->question,
                'question_image'    => $questionImage,
                'audio_file'        => $audioFile,
                'option_1'          => null,
                'option_2'          => null,
                'option_3'          => null,
                'option_4'          => null,
                'option_5'          => null,
                'question_type'     => 'essay',
                'answer'            => null,
                'answers'           => null,
                'essay_answer'      => $request->essay_answer,
                'media_type'        => $request->media_type,
            ]);
        }

        //redirect
        return redirect()->route('admin.exams.show', $exam->id);
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $validatedData = $request->validate([
            'question_type'     => 'required|in:single,multiple,essay',
        ]);

        // Initialize media fields
        $questionImage = null;
        $audioFile = null;

        // Handle image upload
        // Handle image upload
        if ($request->hasFile('question_image') && $request->media_type === 'image') {
            $image = $request->file('question_image');
            $examTitle = str_replace(' ', '_', strtolower($exam->title));
            $timestamp = now()->format('YmdHis'); // Format: YearMonthDayHourMinuteSecond
            $extension = $image->getClientOriginalExtension();
            $questionImage = "{$examTitle}_{$timestamp}.{$extension}";

            // Save directly to public_html/storage/questions
            $image->storeAs('questions', $questionImage, 'public_html');
        }

        // Handle audio upload
        if ($request->hasFile('audio_file') && $request->media_type === 'audio') {
            $audio = $request->file('audio_file');
            $examTitle = str_replace(' ', '_', strtolower($exam->title));
            $timestamp = now()->format('YmdHis'); // Format: YearMonthDayHourMinuteSecond
            $extension = $audio->getClientOriginalExtension();
            $audioFile = "{$examTitle}_{$timestamp}.{$extension}";

            // Save directly to public_html/storage/questions
            $audio->storeAs('questions', $audioFile, 'public_html');
        }


        if ($request->question_type === 'single') {
            Question::create([
                'exam_id'           => $exam->id,
                'question'          => $request->question,
                'question_image'    => $questionImage,
                'audio_file'        => $audioFile,
                'option_1'          => $request->option_1,
                'option_2'          => $request->option_2,
                'option_3'          => $request->option_3,
                'option_4'          => $request->option_4,
                'option_5'          => $request->option_5,
                'question_type'     => 'single',
                'answer'            => $request->answer,
                'answers'           => null,
                'essay_answer'      => null,
                'media_type'        => $request->media_type,
            ]);
        } else if ($request->question_type === 'multiple') {
            Question::create([
                'exam_id'           => $exam->id,
                'question'          => $request->question,
                'question_image'    => $questionImage,
                'audio_file'        => $audioFile,
                'option_1'          => $request->option_1,
                'option_2'          => $request->option_2,
                'option_3'          => $request->option_3,
                'option_4'          => $request->option_4,
                'option_5'          => $request->option_5,
                'question_type'     => 'multiple',
                'answer'            => null,
                'answers'           => $request->answers,
                'essay_answer'      => null,
                'media_type'        => $request->media_type,
            ]);
        } else { // essay
            Question::create([
                'exam_id'           => $exam->id,
                'question'          => $request->question,
                'question_image'    => $questionImage,
                'audio_file'        => $audioFile,
                'option_1'          => null,
                'option_2'          => null,
                'option_3'          => null,
                'option_4'          => null,
                'option_5'          => null,
                'question_type'     => 'essay',
                'answer'            => null,
                'answers'           => null,
                'essay_answer'      => $request->essay_answer,
                'media_type'        => $request->media_type,
            ]);
        }
        return redirect()->route('admin.exams.show', $exam->id);
    }

    public function editQuestion(Exam $exam, Question $question)
    {
        //render with inertia
        return inertia('Admin/Questions/Edit', [
            'exam' => $exam,
            'question' => $question,
        ]);
    }

    public function destroyQuestion(Exam $exam, Question $question)
    {
        if ($question->question_image && Storage::exists('public/questions/' . $question->question_image)) {
            Storage::delete('public/questions/' . $question->question_image);
        }

        if ($question->audio_file && Storage::exists('public/questions/' . $question->audio_file)) {
            Storage::delete('public/questions/' . $question->audio_file);
        }

        $question->delete();
        return redirect()->route('admin.exams.show', $exam->id);
    }

    public function import(Exam $exam)
    {
        return inertia('Admin/Questions/Import', [
            'exam' => $exam
        ]);
    }

    public function storeImport(Request $request, Exam $exam)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // import data
        Excel::import(new QuestionsImport(), $request->file('file'));

        //redirect
        return redirect()->route('admin.exams.show', $exam->id);
    }
}
