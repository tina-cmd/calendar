<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed', // Password confirmation field should be 'password_confirmation'
        ]);

        // Create the user
        $user = User::create([
            'name' => e($validatedData['name']), // Escape special characters
            'email' => e($validatedData['email']),
            'password' => Hash::make($validatedData['password']), // Hash the password
        ]);

        return redirect()->route('get.todolist');
    }

    /**
     * Handle user login.
     */
    public function authenticate(Request $request){
        // Validate the request
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        // Attempt to log in
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('rememberMe');

        // Check if the user exists
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            $request->session()->flush();
            // If user doesn't exist, return with a custom error message
            return back()->withErrors([
                'email' => 'The account with this email address does not exist.',
            ]);
        }

        // If user exists, attempt to log in
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();

            // Redirect to intended page or home
            return redirect()->route('get.todolist');
        }

        $request->session()->flush();
        // If login fails (incorrect password), return with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function account() {
        return Inertia::render('Account');
    }

    public function destroy(Request $request) {
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Redirect to login page or any other page
        return redirect()->route('home', ['success' => 'Logout Successfully.']);
    }

    public function about() {
        return Inertia::render('About');
    }

    public function update_info(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user = Auth::user();
        DB::table('users')->where('id', '=', $user->id)
            ->update(['name' => $validate['name'], 'email' => $validate['email']]);

        return Inertia::location(route('account'));
    }

    public function delete_account(Request $request) {
        $user = Auth::user();
    
        // Delete related records manually
        $user->tasks()->delete(); // Delete user's posts, if you have a posts relationship

        // Delete the user account
        $user->delete();

        // Log the user out
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Redirect to login page or any other page
        return redirect()->route('home', ['success' => 'Deleted Successfully.']);

    }
}
