<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Tutor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TutorController extends Controller
{
    public function index()
    {
        $tutors = Tutor::when(request()->q, function ($tutors) {
            $tutors = $tutors->where('name', 'like', '%' . request()->q . '%')
                ->orWhere('email', 'like', '%' . request()->q . '%')
                ->orWhere('nip', 'like', '%' . request()->q . '%');
        })
            ->latest()
            ->paginate(5);

        $tutors->appends([
            'q' => request()->q
        ]);

        return inertia('Admin/Tutors/Index', [
            'tutors' => $tutors,
            'filters' => [
                'q' => request()->q
            ]
        ]);
    }

    public function create()
    {
        return inertia('Admin/Tutors/Create');
    }

    public function show()
    {
        return inertia('Admin/Tutors/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|max:20|unique:tutors,nip',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:tutors|unique:users',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|string',
            'password' => 'required|confirmed'
        ]);

        DB::transaction(function () use ($request) {
            // Create user with role 2 (tutor)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 2, // 2 = tutor
            ]);

            // Create tutor with user_id reference
            Tutor::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'password' => Hash::make($request->password)
            ]);
        });

        return redirect()->route('admin.tutors.index');
    }

    public function edit($id)
    {
        $tutor = Tutor::findOrFail($id);

        return inertia('Admin/Tutors/Edit', [
            'tutor' => $tutor
        ]);
    }

    public function update(Request $request, Tutor $tutor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:tutors,nip,' . $tutor->id,
            'email' => 'nullable|email|unique:tutors,email,' . $tutor->id . '|unique:users,email,' . $tutor->user_id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|string',
            'password' => 'nullable|confirmed'
        ]);

        DB::transaction(function () use ($request, $tutor) {
            // Update user data
            $user = User::findOrFail($tutor->user_id);
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->password) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Update tutor data
            $tutorData = [
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender
            ];

            if ($request->password) {
                $tutorData['password'] = Hash::make($request->password);
            }

            $tutor->update($tutorData);
        });

        return redirect()->route('admin.tutors.index');
    }

    public function destroy($id)
    {
        $tutor = Tutor::findOrFail($id);

        DB::transaction(function () use ($tutor) {
            // Delete user first
            $user = User::findOrFail($tutor->user_id);
            $user->delete();

            // Then delete tutor
            $tutor->delete();
        });

        return redirect()->route('admin.tutors.index');
    }
}
