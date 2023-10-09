<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        try {
            $todos = Todo::all();
            return response()->json($todos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function byUserId()
    {
        try {
            $todos = Todo::where('user_id', auth()->id())->get();

            return response()->json(['todos' => $todos]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        try {
            $todo = Todo::find($id);
            return response()->json(['todo' => $todo]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request)
    {
        //TODO: make status optional | empty
        try {
            $this->validate($request, [
                'title' => 'required|string|max:255',
                'content' => 'required',
                'status' => 'required|boolean:0,1,true,false',
            ]);

            $todo = Todo::create([
                'title' => $request->title,
                'content' => $request->content,
                'status' => $request->status,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'message' => 'todo successfully added',
                'todo' => $todo,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id){

        try{

            $todo = Todo::where('user_id', auth()->id())->where('id', $id)->first();
            if($todo){
                $todo->title = $request->title;
                $todo->content = $request->content;
                $todo->status = $request->status;

                if($todo->save())
                {
                    return response()->json([
                        'message' => 'Update successful',
                        'todo' => $todo
                    ], 200);
                }

                return response()->json([ 'error' => 'something is wrong' ], 422);
            }

        }catch(\Exception $e){
            return response()->json([ 'error' => $e->getMessage() ], 401);
        }
    }

    public function destroy($id)
    {
        try{
            $todo = Todo::where('user_id', auth()->id())->where('id', $id)->first();
            if($todo){
                $todo->delete();
                return response()->json([
                    'message' => 'Todo deleted'
                ], 200);
            }
        }catch(\Exception $e)
        {
            return response()->json([ 'error' => $e->getMessage() ], 422);
        }

    }
}
