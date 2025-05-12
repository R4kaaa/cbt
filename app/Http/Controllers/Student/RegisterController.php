<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * Show registration form
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get classrooms for dropdown
        $classrooms = Classroom::all();

        // Render registration view
        return inertia('Student/Register/index', [
            'classrooms' => $classrooms,
        ]);
    }

    /**
     * Handle student registration
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name'          => 'required|string|max:255',
            'nisn'          => 'required|unique:students',
            'email'         => 'nullable|email|unique:students',
            'phone'         => 'nullable|string|max:20',
            'gender'        => 'required|string',
            'password'      => 'required|confirmed',
            'classroom_id'  => 'required'
        ]);

        // Create student
        $student = Student::create([
            'name'          => $request->name,
            'nisn'          => $request->nisn,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'gender'        => $request->gender,
            'password'      => $request->password,
            'classroom_id'  => $request->classroom_id
        ]);

        // Automatically login the student after registration
        auth()->guard('student')->login($student);

        // Redirect to student dashboard
        return redirect()->route('student.dashboard');
    }
}
