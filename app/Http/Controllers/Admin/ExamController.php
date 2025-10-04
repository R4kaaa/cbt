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
            'question_type'     => 'required|in:single,multiple,essay',
            'option_type'       => 'nullable|in:text,image',
            'option_1_image'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'option_2_image'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'option_3_image'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'option_4_image'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'option_5_image'    => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $questionImage = $question->question_image;
        $audioFile = $question->audio_file;

        if ($request->media_type === 'image') {
            if ($request->hasFile('question_image')) {
                if ($question->question_image && Storage::disk('public_html')->exists('questions/' . $question->question_image)) {
                    Storage::disk('public_html')->delete('questions/' . $question->question_image);
                }

                $image = $request->file('question_image');
                $examTitle = str_replace(' ', '_', strtolower($exam->title));
                $timestamp = now()->format('YmdHis');
                $extension = $image->getClientOriginalExtension();
                $questionImage = "{$examTitle}_{$timestamp}.{$extension}";
                $image->storeAs('questions', $questionImage, 'public_html');
            }
            $audioFile = null;
        } else if ($request->media_type === 'audio') {
            if ($request->hasFile('audio_file')) {
                if ($question->audio_file && Storage::disk('public_html')->exists('questions/' . $question->audio_file)) {
                    Storage::disk('public_html')->delete('questions/' . $question->audio_file);
                }

                $audio = $request->file('audio_file');
                $examTitle = str_replace(' ', '_', strtolower($exam->title));
                $timestamp = now()->format('YmdHis');
                $extension = $audio->getClientOriginalExtension();
                $audioFile = "{$examTitle}_{$timestamp}.{$extension}";
                $audio->storeAs('questions', $audioFile, 'public_html');
            }
            $questionImage = null;
        } else if ($request->media_type === 'none') {
            if ($question->question_image && Storage::disk('public_html')->exists('questions/' . $question->question_image)) {
                Storage::disk('public_html')->delete('questions/' . $question->question_image);
            }
            if ($question->audio_file && Storage::disk('public_html')->exists('questions/' . $question->audio_file)) {
                Storage::disk('public_html')->delete('questions/' . $question->audio_file);
            }
            $questionImage = null;
            $audioFile = null;
        }

        $option1 = $question->option_1;
        $option2 = $question->option_2;
        $option3 = $question->option_3;
        $option4 = $question->option_4;
        $option5 = $question->option_5;

        if ($request->option_type === 'image') {
            $examTitle = str_replace(' ', '_', strtolower($exam->title));

            if ($request->hasFile('option_1_image')) {
                if ($question->option_type === 'image' && $question->option_1 && Storage::disk('public_html')->exists('questions/options/' . $question->option_1)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_1);
                }
                $file = $request->file('option_1_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option1 = "{$examTitle}_option1_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option1, 'public_html');
            }

            if ($request->hasFile('option_2_image')) {
                if ($question->option_type === 'image' && $question->option_2 && Storage::disk('public_html')->exists('questions/options/' . $question->option_2)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_2);
                }
                $file = $request->file('option_2_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option2 = "{$examTitle}_option2_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option2, 'public_html');
            }

            if ($request->hasFile('option_3_image')) {
                if ($question->option_type === 'image' && $question->option_3 && Storage::disk('public_html')->exists('questions/options/' . $question->option_3)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_3);
                }
                $file = $request->file('option_3_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option3 = "{$examTitle}_option3_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option3, 'public_html');
            }

            if ($request->hasFile('option_4_image')) {
                if ($question->option_type === 'image' && $question->option_4 && Storage::disk('public_html')->exists('questions/options/' . $question->option_4)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_4);
                }
                $file = $request->file('option_4_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option4 = "{$examTitle}_option4_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option4, 'public_html');
            }

            if ($request->hasFile('option_5_image')) {
                if ($question->option_type === 'image' && $question->option_5 && Storage::disk('public_html')->exists('questions/options/' . $question->option_5)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_5);
                }
                $file = $request->file('option_5_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option5 = "{$examTitle}_option5_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option5, 'public_html');
            }
        } else {
            if ($question->option_type === 'image') {
                if ($question->option_1 && Storage::disk('public_html')->exists('questions/options/' . $question->option_1)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_1);
                }
                if ($question->option_2 && Storage::disk('public_html')->exists('questions/options/' . $question->option_2)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_2);
                }
                if ($question->option_3 && Storage::disk('public_html')->exists('questions/options/' . $question->option_3)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_3);
                }
                if ($question->option_4 && Storage::disk('public_html')->exists('questions/options/' . $question->option_4)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_4);
                }
                if ($question->option_5 && Storage::disk('public_html')->exists('questions/options/' . $question->option_5)) {
                    Storage::disk('public_html')->delete('questions/options/' . $question->option_5);
                }
            }

            $option1 = $request->option_1;
            $option2 = $request->option_2;
            $option3 = $request->option_3;
            $option4 = $request->option_4;
            $option5 = $request->option_5;
        }

        if ($request->question_type === 'single') {
            $question->update([
                'question'          => $request->question,
                'question_image'    => $questionImage,
                'audio_file'        => $audioFile,
                'option_1'          => $option1,
                'option_2'          => $option2,
                'option_3'          => $option3,
                'option_4'          => $option4,
                'option_5'          => $option5,
                'question_type'     => 'single',
                'answer'            => $request->answer,
                'answers'           => null,
                'essay_answer'      => null,
                'media_type'        => $request->media_type,
                'option_type'       => $request->option_type ?? 'text',
            ]);
        } else if ($request->question_type === 'multiple') {
            $question->update([
                'question'          => $request->question,
                'question_image'    => $questionImage,
                'audio_file'        => $audioFile,
                'option_1'          => $option1,
                'option_2'          => $option2,
                'option_3'          => $option3,
                'option_4'          => $option4,
                'option_5'          => $option5,
                'question_type'     => 'multiple',
                'answer'            => null,
                'answers'           => $request->answers,
                'essay_answer'      => null,
                'media_type'        => $request->media_type,
                'option_type'       => $request->option_type ?? 'text',
            ]);
        } else {
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
                'option_type'       => 'text',
            ]);
        }

        return redirect()->route('admin.exams.show', $exam->id);
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'question_type' => 'required|in:single,multiple,essay',
            'option_type' => 'nullable|in:text,image',
            'option_1_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'option_2_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'option_3_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'option_4_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'option_5_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $questionImage = null;
        $audioFile = null;

        if ($request->hasFile('question_image') && $request->media_type === 'image') {
            $image = $request->file('question_image');
            $examTitle = str_replace(' ', '_', strtolower($exam->title));
            $timestamp = now()->format('YmdHis');
            $extension = $image->getClientOriginalExtension();
            $questionImage = "{$examTitle}_{$timestamp}.{$extension}";
            $image->storeAs('questions', $questionImage, 'public_html');
        }

        if ($request->hasFile('audio_file') && $request->media_type === 'audio') {
            $audio = $request->file('audio_file');
            $examTitle = str_replace(' ', '_', strtolower($exam->title));
            $timestamp = now()->format('YmdHis');
            $extension = $audio->getClientOriginalExtension();
            $audioFile = "{$examTitle}_{$timestamp}.{$extension}";
            $audio->storeAs('questions', $audioFile, 'public_html');
        }

        $option1 = null;
        $option2 = null;
        $option3 = null;
        $option4 = null;
        $option5 = null;

        if ($request->option_type === 'image') {
            $examTitle = str_replace(' ', '_', strtolower($exam->title));

            if ($request->hasFile('option_1_image')) {
                $file = $request->file('option_1_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option1 = "{$examTitle}_option1_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option1, 'public_html');
            }

            if ($request->hasFile('option_2_image')) {
                $file = $request->file('option_2_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option2 = "{$examTitle}_option2_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option2, 'public_html');
            }

            if ($request->hasFile('option_3_image')) {
                $file = $request->file('option_3_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option3 = "{$examTitle}_option3_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option3, 'public_html');
            }

            if ($request->hasFile('option_4_image')) {
                $file = $request->file('option_4_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option4 = "{$examTitle}_option4_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option4, 'public_html');
            }

            if ($request->hasFile('option_5_image')) {
                $file = $request->file('option_5_image');
                $timestamp = now()->format('YmdHis') . '_' . rand(1000, 9999);
                $extension = $file->getClientOriginalExtension();
                $option5 = "{$examTitle}_option5_{$timestamp}.{$extension}";
                $file->storeAs('questions/options', $option5, 'public_html');
            }
        } else {
            $option1 = $request->option_1;
            $option2 = $request->option_2;
            $option3 = $request->option_3;
            $option4 = $request->option_4;
            $option5 = $request->option_5;
        }

        if ($request->question_type === 'single') {
            Question::create([
                'exam_id' => $exam->id,
                'question' => $request->question,
                'question_image' => $questionImage,
                'audio_file' => $audioFile,
                'option_1' => $option1,
                'option_2' => $option2,
                'option_3' => $option3,
                'option_4' => $option4,
                'option_5' => $option5,
                'question_type' => 'single',
                'answer' => $request->answer,
                'answers' => null,
                'essay_answer' => null,
                'media_type' => $request->media_type,
                'option_type' => $request->option_type ?? 'text',
            ]);
        } else if ($request->question_type === 'multiple') {
            Question::create([
                'exam_id' => $exam->id,
                'question' => $request->question,
                'question_image' => $questionImage,
                'audio_file' => $audioFile,
                'option_1' => $option1,
                'option_2' => $option2,
                'option_3' => $option3,
                'option_4' => $option4,
                'option_5' => $option5,
                'question_type' => 'multiple',
                'answer' => null,
                'answers' => $request->answers,
                'essay_answer' => null,
                'media_type' => $request->media_type,
                'option_type' => $request->option_type ?? 'text',
            ]);
        } else {
            Question::create([
                'exam_id' => $exam->id,
                'question' => $request->question,
                'question_image' => $questionImage,
                'audio_file' => $audioFile,
                'option_1' => null,
                'option_2' => null,
                'option_3' => null,
                'option_4' => null,
                'option_5' => null,
                'question_type' => 'essay',
                'answer' => null,
                'answers' => null,
                'essay_answer' => $request->essay_answer,
                'media_type' => $request->media_type,
                'option_type' => 'text',
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
