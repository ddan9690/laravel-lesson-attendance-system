<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;

class TeacherController
{
    public function index()
    {
        $teachers = User::orderBy('name')->get();
        $roles = Role::whereNotIn('name', ['class_teacher', 'class_supervisor'])
            ->pluck('name') // get role names
            ->mapWithKeys(fn($r) => [$r => ucwords(str_replace('_', ' ', $r))]) // clean display names
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
        ]);

        try {
            $code = substr($request->phone, -4);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'code' => $code,
                'slug' => Str::slug($request->name) . '-' . time(),
                'password' => Hash::make($request->phone),
            ]);

            $user->assignRole('teacher');
            event(new Registered($user));

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher created successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()
                ->withErrors($e->getMessage());
        }
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
            'name' => 'required|string|max:255',
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
