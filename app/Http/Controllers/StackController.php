<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stack;

class StackController extends Controller
{
    /**
     * Get all stacks
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        return response()->json([
            'message' => 'Success!',
            'data' => [
                'stacks' => Stack::all()
            ]
        ], 200);
    }

    /**
     * Adds new stack to the database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255|unique:stacks,name'
        ]);
        $stack = new Stack;
        $stack->name = $data['name'];

        \DB::transaction(function () use ($stack) {
            $stack->save();
        });

        if ($stack->exists) {
            return response()->json([
                'message' => 'Stack added successfully!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Stack could not be created!'
            ], 503);
        }
    }
}
