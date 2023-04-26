<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\Tasks;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // fetch and return all projects and tasks
        $tasks    = Tasks::orderBy('priority', 'asc')->get();
        // $projects = Projects::all(); 
        $projects = Projects::hasTasks()->get(); 

        return view('tasks.index', compact('tasks', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $projects = Projects::all();
        $users    = User::all();

        return view('tasks.create', compact('users', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate request parameters
        $request->validate([
            'name'       => 'required',
            'user_id'    => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $maxPrioroty = Tasks::max('priority') ?: 0;

        $newTask             = new Tasks();
        $newTask->name       = $request->name;
        $newTask->project_id = $request->project_id;
        $newTask->priority   = ++$maxPrioroty;

        $newTask->save();

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function show(Tasks $tasks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $task     = Tasks::findOrFail($id);
        $projects = Projects::all();
        $users    = User::all();
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $task = Tasks::findOrFail($id);

        $request->validate([
            'name'       => 'required',
            'user_id'    => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        $task->name       = $request->name;
        $task->user_id    = $request->user_id;
        $task->project_id = $request->project_id;

        $task->save();

        return redirect()->route('tasks.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // find by id
        $task = Tasks::findOrFail($id);

        Tasks::where('priority', '>', $task->priority)
            ->update(['priority' => DB::raw('priority - 1')]);

        $task->delete();

        return redirect()->route('tasks.index');
    }

    public function apiSetPriority(Request $request){

        $task = Tasks::findOrFail($request->input('task_id'));
        $prev = Tasks::find( $request->input('prev_id') );

        if( !$request->input('prev_id') ){            
            $destination = 1;
        }else if( !$request->input('next_id') ){
            $destination = Tasks::count();
        }else{
            $destination = $task->priority < $prev->priority ? $prev->priority : $prev->priority + 1;
        }

       
        Tasks::where('priority', '>', $task->priority)
            ->where('priority', '<=', $destination)
            ->update(['priority' => DB::raw('priority - 1')]);

        Tasks::where('priority', '<', $task->priority)
            ->where('priority', '>=', $destination)
            ->update(['priority' => DB::raw('priority + 1')]);

        $task->priority = $destination;
        $task->save();

        return response()->json(true);
    }
}
