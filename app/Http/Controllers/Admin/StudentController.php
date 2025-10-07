<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index()
    {
        //get all classrooms for filter dropdown
        $classrooms = Classroom::all();

        //get students with filters
        $students = Student::when(request()->q, function ($students) {
            $students = $students->where('name', 'like', '%' . request()->q . '%')
                ->orWhere('email', 'like', '%' . request()->q . '%')
                ->orWhere('nisn', 'like', '%' . request()->q . '%');
        })
            ->when(request()->classroom_id, function ($students) {
                $students = $students->where('classroom_id', request()->classroom_id);
            })
            ->with('classroom')
            ->latest()
            ->paginate(5);

        //append query string to pagination links
        $students->appends([
            'q' => request()->q,
            'classroom_id' => request()->classroom_id
        ]);

        //render with inertia
        return inertia('Admin/Students/Index', [
            'students' => $students,
            'classrooms' => $classrooms,
            'filters' => [
                'q' => request()->q,
                'classroom_id' => request()->classroom_id
            ]
        ]);
    }

    public function create()
    {
        //get classrooms
        $classrooms = Classroom::all();

        //render with inertia
        return inertia('Admin/Students/Create', [
            'classrooms' => $classrooms,
        ]);
    }

    public function store(Request $request)
    {
        //validate request
        $request->validate([
            'nik' => 'required|unique:students,nisn',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students',
            'phone' => 'nullable|string|max:20|unique:students,phone',
            'gender' => 'required|string',
            'password' => 'required|confirmed',
            'classroom_id' => 'required'
        ]);


        //create student
        Student::create([
            'name'          => $request->name,
            'nisn'          => $request->nik,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'gender'        => $request->gender,
            'password'      => $request->password,
            'classroom_id'  => $request->classroom_id
        ]);

        //redirect
        return redirect()->route('admin.students.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //get student
        $student = Student::findOrFail($id);

        //get classrooms
        $classrooms = Classroom::all();

        //render with inertia
        return inertia('Admin/Students/Edit', [
            'student' => $student,
            'classrooms' => $classrooms,
        ]);
    }

    public function update(Request $request, Student $student)
    {
        // dd($student->id);
        //validate request
        $request->validate([
            'name'          => 'required|string|max:255',
            'nisn'          => 'required|unique:students,nisn,' . $student->id,
            'email'         => 'nullable|email|unique:students,email,' . $student->id,
            'phone'         => 'nullable|string|max:20',
            'gender'        => 'required|string',
            'classroom_id'  => 'required',
            'password'      => 'nullable|confirmed'
        ]);

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
        return redirect()->route('admin.students.index');
    }

    public function destroy($id)
    {
        //get student
        $student = Student::findOrFail($id);

        //delete student
        $student->delete();

        //redirect
        return redirect()->route('admin.students.index');
    }

    public function import()
    {
        return inertia('Admin/Students/Import');
    }

    public function storeImport(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // import data
        Excel::import(new StudentsImport(), $request->file('file'));

        //redirect
        return redirect()->route('admin.students.index');
    }
}
