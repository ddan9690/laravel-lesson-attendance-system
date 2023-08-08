<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Week;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Mail\RegistrationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function index()
    {

        $users = User::orderBy('name', 'asc')
            ->paginate(15);

        return view('remedial.pages.users.index', compact('users'));
    }

    public function create()
    {

        return view('remedial.pages.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'phone' => ['required', 'regex:/^07\d{8}$/'],
        ]);

        try {
            // $phone = $request->phone;
            // $code = substr($phone, -3); // get the last three digits of the phone
            // $password = $phone;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'code' => substr($request->phone, -4),
                'password' => Hash::make($request->phone),
            ]);

            event(new Registered($user));
            // \Mail::to($user->email)->send(new RegistrationMail($user));
            return redirect()->route('users.index')->withMessage('Successfully Created!');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

    public function changepassword()
    {
        return view('remedial.pages.changepassword');
    }



    // Store the new password in storage
    public function storepassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|different:current_password|confirmed',
            'code' => 'required|string',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $userCode = $user->code;
        if ($request->code !== $userCode) {
            return redirect()->back()->withErrors(['code' => 'The code entered is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->last_login = now();
        $user->update();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }


    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->withMessage('Successfully Deleted!');
        } catch (QueryException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('remedial.pages.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => ['required', 'regex:/^07\d{8}$/'],
            'role' => 'required|in:user,admin,super',
        ]);

        // $user->update($validatedData);
        $user->update($request->all());


        return redirect()->route('users.index')->with('message', 'User updated successfully.');
    }


    public function mylessons()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get all the weeks
        $weeks = Week::orderBy('week_number')->get();

        // Initialize an empty array to hold attendance counts for each week
        $attendances = [];

        // Loop through each week and count the attendance for the authenticated user
        foreach ($weeks as $week) {
            $count = $user->attendances->where('week_id', $week->id)->count();
            $attendances[$week->week_number] = $count;
        }

        // Pass the user, weeks, and attendance counts to the view
        return view('remedial.pages.attendances.user.allweeks', compact('user', 'weeks', 'attendances'));
    }

    public function userweekly($week)
    {
        $user = Auth::user();
        $week = Week::where('week_number', $week)->firstOrFail();
        $attendances = Attendance::where('user_id', $user->id)
            ->where('week_id', $week->id)
            ->with('form', 'subject', 'lesson')
            ->orderBy('created_at')
            ->get();
        return view('remedial.pages.attendances.user.weekdetails', compact('user', 'week', 'attendances'));
    }


}
