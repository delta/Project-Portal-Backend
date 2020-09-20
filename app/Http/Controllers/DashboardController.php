<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

class DashboardController extends Controller
{
    /**
     * Show all details, projects and feedbacks of specific user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
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
            "data" => [
                'user' => $user,
                'open_projects' => Project::whereHas('status', function($query) {
                    $query->where('name', 'ONGOING');
                })->withCount('users')
                ->with([
                    'stacks',
                    'status',
                    'type'
                ])->get()->filter(function ($project) {
                    return $project->users_count < $project->max_member_count;
                })
            ]
        ], 200);
    }
}
