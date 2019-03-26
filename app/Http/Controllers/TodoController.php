<?php

namespace App\Http\Controllers;

use Auth;
use App\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TodoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $todos = Auth::user()->todos;

        return view('todos.index', compact('todos'));
    }

    public function create()
    {
        return view('todos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['item' => 'required|between:2,50']);

        Auth::user()->todos()->save(new Todo($data));

        return redirect()->route('todos.index')->withStatus('Todo saved!');
    }

    public function update(Request $request, Todo $todo)
    {
        $data = $request->validate(['done' => 'required|boolean']);

        $todo->done = $data['done'];
        $todo->completed_on = $data['done'] == true ? Carbon::now() : null;

        return response(['status' => $todo->save() ? 'success' : 'error']);
    }

    public function destroy(Todo $todo)
    {
        return response(['status' => $todo->delete() ? 'success' : 'error']);
    }
}
