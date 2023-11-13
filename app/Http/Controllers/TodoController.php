<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Inertia\Inertia;

class TodoController extends Controller
{
    public function index()
    {
        return Inertia::render('Todo/Index', [
            'todos' => Todo::with('user')->get(),
        ]);
    }

    public function update(TodoRequest $request, Todo $todo)
    {
        return $todo->update($request->validated());
    }

    public function destroy(Todo $todo)
    {
        return $todo->delete();
    }
}
