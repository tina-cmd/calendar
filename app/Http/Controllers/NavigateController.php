<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;

class NavigateController extends Controller
{
    public function index(Request $request) {
        
        if(Auth::check()) {
            return redirect()->route('get.todolist');
        }

        $success = $request->success;
        $warning = $request->warning;
        // Optionally clear the warning from the request object
        $request->merge(['warning' => null]);
        $request->merge(['success' => null]);
        
        return Inertia::render('Index', ['warning' => $warning, 'success' => $success]);
    }

    public function todolist() {
        $user = Auth::user();
        // $value = $user->tasks;
        $value = Task::where('user_id', '=', $user->id)->get(); // Replace 1 with the user ID
        // dd($value); 
        return Inertia::render('TodoList', ['lists' => $value]);
    }

}
