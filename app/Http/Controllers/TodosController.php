<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodosController extends Controller
{
    public function __construct()
    {
        $this->middleware('customAuth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(auth()->user()->todos()->orderBy('order', 'ASC')->get());
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(["task" => "required|max:255", 'order' => 'required']);
        $todo = new Todo(['task' => $request->get('task'), 'completed' => false, 'priority' => 'low', 'order' => $request->get('order')]);
        auth()->user()->todos()->save($todo);

        return response()->json($todo, 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);
        if($todo === null) return response()->json(["error" => "Bad request"], 404);

        $user = auth()->user();
        if($todo->user->id !== $user->id) return response()->json(["error" => "Bad request"], 404);

        $request->validate(["task" => "required|max:255"]);
        $todo->update(['task' => $request->get('task'), 'completed' => $request->get('completed'), 'priority' => $request->get('priority')]);

        return $todo;
    }

    private function canUserUpdateTodos($todos) {
        $user_ids = array_column($todos, 'user_id');
        $user = auth()->user();

        foreach ($user_ids as $user_id) {
            if($user->id !== $user_id) return response()->json(['error' => "Unatuhorized"], 401);
        }
    }

    private function mapTodosToIds($todos) {
        $todosMap = array();

        foreach ($todos as $todo) {
            $castedTodo = (object) $todo;
            $todosMap += [$castedTodo->id => $castedTodo];
        }

        return $todosMap;
    }

    private function updateTodos($todos, $todosMap) {
        $ids = array_column($todos, 'id');
        $foundTodos = Todo::whereIn('id', $ids)->get();
        foreach ($foundTodos as $todoToUpdate) {
            $todoToUpdate->update(['order' => $todosMap[$todoToUpdate->id]->order, 'priority' => $todosMap[$todoToUpdate->id]->priority]);
        }
    }

    public function updateMany(Request $request) {
        $todos = $request->get('todos');

        $this->canUserUpdateTodos($todos);
        $todosMap = $this->mapTodosToIds($todos);
        $this->updateTodos($todos, $todosMap);

        return $todosMap;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::find($id);
        if($todo === null) return response()->json(["error" => "Bad request"], 404);

        $user = auth()->user();
        if($todo->user->id !== $user->id) return response()->json(["error" => "Bad request"], 404);

        $todo->delete();

        return response()->json([],200);

    }
}
