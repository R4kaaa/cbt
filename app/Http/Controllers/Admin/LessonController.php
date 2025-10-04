<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::when(request()->q, function ($lessons) {
            $lessons = $lessons->where('title', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);
        $lessons->appends(['q' => request()->q]);
        return inertia('Admin/Lessons/Index', [
            'lessons' => $lessons,
        ]);
    }
    public function create()
    {
        return inertia('Admin/Lessons/Create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|unique:lessons',
            'kkm' => 'required|integer|min:0|max:250',
        ]);

        Lesson::create([
            'title' => $request->title,
            'kkm'   => $request->kkm,
        ]);

        return redirect()->route('admin.lessons.index');
    }
    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id);
        return inertia('Admin/Lessons/Edit', [
            'lesson' => $lesson,
        ]);
    }
    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|unique:lessons,title,' . $lesson->id,
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        $lesson->update([
            'title' => $request->title,
            'kkm'   => $request->kkm,
        ]);

        return redirect()->route('admin.lessons.index');
    }
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();
        return redirect()->route('admin.lessons.index');
    }
}
