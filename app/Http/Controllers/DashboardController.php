<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show all details, projects and feedbacks of specific user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        $user = $request->user();
        $user['projects'] = $user->projects()->with([
            'feedbacks' => function ($feedback) use ($user) {
                $feedback->where('sender_id', $user)
                         ->orWhere('receiver_id', $user);
            },
            'stacks',
            'status',
            'users',
            'type'
        ])->get();
        return response()->json([
            "message" => "Success!",
            "data" => $user
        ], 200);
    }
}
