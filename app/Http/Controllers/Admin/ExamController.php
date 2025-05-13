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
    public function storeQuestion(Request $request, Exam $exam)
    {
        // dd($exam->all());
        $request->validate([
            'question'          => 'required',
            'option_1'          => 'required',
            'option_2'          => 'required',
            'option_3'          => 'required',
            'option_4'          => 'required',
            'option_5'          => 'required',
            'question_type'     => 'required|in:single,multiple',
        ]);

        if ($request->question_type === 'single') {
            Question::create([
                'exam_id'           => $exam->id,
                'question'          => $request->question,
                'option_1'          => $request->option_1,
                'option_2'          => $request->option_2,
                'option_3'          => $request->option_3,
                'option_4'          => $request->option_4,
                'option_5'          => $request->option_5,
                'question_type'     => 'single',
                'answer'            => $request->answer,
                'answers'           => null,
            ]);
        } else {
            Question::create([
                'exam_id'           => $exam->id,
                'question'          => $request->question,
                'option_1'          => $request->option_1,
                'option_2'          => $request->option_2,
                'option_3'          => $request->option_3,
                'option_4'          => $request->option_4,
                'option_5'          => $request->option_5,
                'question_type'     => 'multiple',
                'answer'            => null,
                'answers'           => $request->answers,
            ]);
        }
        return redirect()->route('admin.exams.show', $exam->id);
    }
    public function updateQuestion(Request $request, Exam $exam, Question $question)
    {
        $request->validate([
            'question'          => 'required',
            'option_1'          => 'required',
            'option_2'          => 'required',
            'option_3'          => 'required',
            'option_4'          => 'required',
            'option_5'          => 'required',
            'question_type'     => 'required|in:single,multiple'
        ]);
        if ($request->question_type === 'single') {
            $question->update([
                'question'          => $request->question,
                'option_1'          => $request->option_1,
                'option_2'          => $request->option_2,
                'option_3'          => $request->option_3,
                'option_4'          => $request->option_4,
                'option_5'          => $request->option_5,
                'question_type'     => 'single',
                'answer'            => $request->answer,
                'answers'           => null,
            ]);
        } else {
            $question->update([
                'question'          => $request->question,
                'option_1'          => $request->option_1,
                'option_2'          => $request->option_2,
                'option_3'          => $request->option_3,
                'option_4'          => $request->option_4,
                'option_5'          => $request->option_5,
                'question_type'     => 'multiple',
                'answer'            => null,
                'answers'           => $request->answers,
            ]);
        }

        //redirect
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
