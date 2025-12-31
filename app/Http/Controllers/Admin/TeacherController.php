<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;

class TeacherController
{
    public function index()
    {
        // Load all teachers
        $teachers = User::role('teacher')
            ->orderBy('name')
            ->get();

        // Roles for display (exclude class_teacher and class_supervisor)
        $roles = Role::whereNotIn('name', ['class_teacher', 'class_supervisor'])
            ->pluck('name')
            ->mapWithKeys(fn($r) => [$r => ucwords(str_replace('_', ' ', $r))])
            ->toArray();

        return view('admin.teachers.index', compact('teachers', 'roles'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
        ]);

        $code = substr($request->phone, -4);

        $teacher = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'code'     => $code,
            'slug'     => Str::slug($request->name) . '-' . time(),
            'password' => Hash::make($request->phone), // default password is phone
        ]);

        $teacher->assignRole('teacher');
        event(new Registered($teacher));

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher created successfully. Subjects can be assigned later.');
    }

    private function findTeacher($id, $slug)
    {
        return User::where('id', $id)->where('slug', $slug)->firstOrFail();
    }

    public function edit($id, $slug)
    {
        $teacher = $this->findTeacher($id, $slug);
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $id, $slug)
    {
        $teacher = $this->findTeacher($id, $slug);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'slug' => Str::slug($request->name) . '-' . time(),
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy($id, $slug)
    {
        $teacher = $this->findTeacher($id, $slug);
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }

    public function changeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,name',
        ]);

        $teacher = User::findOrFail($request->user_id);
        $teacher->syncRoles([$request->role]);

        return response()->json(['success' => true, 'message' => 'Role updated successfully.']);
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $teacher = Auth::user();
        
        $teacher->password = Hash::make($request->new_password);
        $teacher->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
