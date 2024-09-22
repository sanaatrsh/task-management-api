<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id =  Auth::user()->id;
        if (Auth::user()->role == 'admin')
            $tasks = Task::all();
        else if (Auth::user()->role == 'manager')
            $tasks = Task::where('created_by', $id);
        else if (Auth::user()->role == 'employee')
            $tasks = Task::where('assigned_to', $id)->get();



        return TaskResource::collection(collect($tasks));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        // dd(Auth::user()->role);
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'manager') {
            $task = $request->validated();
            Task::create($task);

            return response()->json([
                'massage' => 'task added successfully',
                'post' => $task,
            ]);
        } else {
            return response()->json([
                'massage' => 'Access denied',
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function Assign(Request $request, string $id)
    {
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'manager') {
            $task = Task::findOrFail($id);
            $validate = $request->validate([
                "assigned_to" => 'required|exists:users,id',
            ]);
            $task->update($validate);

            return response()->json([
                'massage' => 'task assigned successfully',
                'assigned_by' => Auth::user()->id,
                'assigned_to' => $task->assigned_to,
                'post' => $task,
            ]);
        } else {
            return response()->json([
                'massage' => 'Access denied',
            ], 401);
        }
    }
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);
        if (Auth::user()->role == 'employee' &&  $task->assigned_to == Auth::user()->id) {
            $validate = $request->validate([
                "status" => 'required|in:panding,in progress,completed',
            ]);
            $task->update($validate);

            return response()->json([
                'massage' => 'task status updated successfully',
                'assigned_to' => $task->assigned_to,
                'status' => $task->status,
                'post' => $task,
            ]);
        } else {
            return response()->json([
                'massage' => 'Access denied',
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->role == 'admin') {
            $task = Task::findOrFail($id);
            $task->delete();
            return response()->json([
                'massage' => 'task deleted successfully',
            ]);
        }
        return response()->json([
            'massage' => 'Access denied',
        ], 401);
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $query = Task::query();

        if ($request->has('status')) {
            $tasks =  $query->where('status', $request->input('status'))->get();
        }

        if ($request->has('sort_by_due_date')) {
            $sortOrder = $request->input('sort_by_due_date') === 'asc' ? 'asc' : 'desc';
            $tasks = $query->orderBy('due_date', $sortOrder)->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $tasks
        ], 200);
    }
}
