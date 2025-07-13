<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($id)
    {
        //get student
        $student = Student::findOrFail($id);

        //get classrooms
        $classrooms = Classroom::all();

        //render with inertia
        return inertia('Student/Profile/Index', [
            'student' => $student,
            'classrooms' => $classrooms,
        ]);
    }
    public function update(Request $request, $student)
    {
        // dd($request->all());
        $student = Student::findOrFail($student);
        //validate request
        $request->validate([
            'name'          => 'required|string|max:255',
            'nisn'          => 'required|size:16|unique:students,nisn,' . (int)$student->id,
            'phone'         => 'nullable|string|max:20',
            'gender'        => 'required|string',
            'classroom_id'  => 'required',
            'password'      => 'nullable|confirmed'
        ]);
        // dd($request->all());
        //check password
        if ($request->password == "") {
            //update student without password
            $student->update([
                'name'          => $request->name,
                'nisn'          => $request->nisn,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'gender'        => $request->gender,
                'classroom_id'  => $request->classroom_id
            ]);
        } else {
            //update student with password
            $student->update([
                'name'          => $request->name,
                'nisn'          => $request->nisn,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'gender'        => $request->gender,
                'password'      => $request->password,
                'classroom_id'  => $request->classroom_id
            ]);
        }

        //redirect
        return redirect()->route('student.profile.index', $student->id);
    }
}
