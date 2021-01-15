<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Todo $todo)
    {
        return $todo::where('user_id', auth()->user()->id)->latest()->paginate(5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Todo $todo)
    {
        $checking = Todo::where('title', $request->title)
                                ->where('user_id', auth()->user()->id)
                                ->where('completed', 0)
                                ->count();
        if(!$checking)
        {
            $todo->user_id = auth()->user()->id;
            $todo->title = $request->title;
            $todo->save();
            return 1;
        }
        else
        {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Hey dude you have another uncompleted task like this one'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $todo = Todo::findOrFail($id);

        if($request->completed)
        {
            $todo->completed = $request->completed;
            $todo->title = $request->title;
            $todo->save();
            return 1;
        }
        if($request->description)
        {
            $todo->completed = $request->completed;
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->save();
            return 1;
        }
        else
        {
            $checking = Todo::where('title', $request->title)
                                ->where('user_id', auth()->user()->id)
                                ->where('completed', 0)
                                ->count();
            if(!$checking)
            {
                $todo->completed = $request->completed;
                $todo->title = $request->title;
                $todo->description = $request->description;
                $todo->save();
                return 1;
            }
            else
            {
                return response()->json([
                    'status' => 'error',
                    'msg'    => 'Hey dude you have another uncompleted task like this one'
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        $todo = Todo::where('user_id', auth()->user()->id)->get();
        return $todo;
    }
}
