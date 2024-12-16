<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function post(Request $request) {
        $u_id = Auth::user()->id;
        $validate = $request->validate([
            'text' => 'required',
            'date' => 'required|date', // Validates as a valid date in 'YYYY-MM-DD'
            'time' => 'required|date_format:H:i', // Validates as a valid time in 'HH:MM' format
        ]);

        $formatDate = Carbon::parse($validate['date'])->toDateString();
        $formattedTime = Carbon::parse($validate['time'])->format('H:i:s');

        // dd($validate['time']);
        Task::create([
            'user_id' => $u_id,
            'task_name' => $validate['text'],
            'due_date' => $formatDate,
            'due_time' => $formattedTime ,
            'created_at' => Carbon::now(),
        ]);

        // Fetch updated tasks list
        $tasks = Task::where('user_id', '=', Auth::user()->id)->get();

        
        return Inertia::location(route('get.todolist'));
    }

    public function destroy(Request $request) {
        // dd($request->task['id']);
        $task = DB::table('tasks')->where('id', '=', $request->task['id'])->delete();

        return Inertia::location(route('get.todolist'));
    }

    public function update(Request $request) {
        $tasks = $request->validate([
            'id' => 'required',
            'text' => 'required',
            'time' => 'required|date_format:H:i',
        ]);

        $formattedTime = Carbon::parse($tasks['time'])->format('H:i:s');

        DB::table('tasks')
            ->where('id', '=', $tasks['id'])
            ->update(['task_name' => $tasks['text'], 'due_time' => $formattedTime]);

        return Inertia::location(route('get.todolist'));
    }

    public function edit_status(Request $request) {
        $task = Task::findOrFail($request->id);

        if($request->status === 'pending') {
            $task->status = 'completed';
        } else if($request->status === 'completed') {
            $task->status = 'pending';
        }
        
        $task->save();

        return Inertia::location(route('get.todolist'));
    }

    public function expired_status(Request $request) {
        // Ensure the request contains an 'id' array
        if ($request->has('id') && is_array($request->id)) {
            foreach ($request->id as $id) {
                // Find the task or fail if not found
                $task = Task::findOrFail($id);
                $task->status = 'expired';
                $task->save();
            }
        }
    
        // Redirect to the 'get.todolist' route
        return Inertia::location(route('get.todolist'));
    }
    
}
