<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index($project_id)
    {
        try {
            Project::findOrFail($project_id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project doesn\'t exist!'
            ], 404);
        }
        if (Project::find($project_id)) {
            $feedbacks_sent = Project::find($project_id)->feedbacks()->where('sender_id', auth()->user()->id)->get();
            $feedbacks_recieved = Project::find($project_id)->feedbacks()->where('receiver_id', auth()->user()->id)->get();

            return response([
                'message' => "Successfully fetched feedbacks",
                'feedback_sent' => $feedbacks_sent,
                'feedback_received' => $feedbacks_recieved
            ]);

        } else {
            return response()->json([
                'message' => 'Project not Found'
            ], 404);
        }
    }

    public function add($project_id, Request $request)
    {
        try {
            Project::findOrFail($project_id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project doesn\'t exist!'
            ], 404);
        }
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|max:1000'
        ]);

        if (count(DB::table('project_user')->where([
            ['project_id', '=' ,$project_id],
            ['user_id', '=' ,auth()->user()->id]
            ])->get()) != 0){
            $feedback = new Feedback;

            $feedback->project_id = $project_id;
            $feedback->sender_id = auth()->user()->id;
            $feedback->receiver_id = $request->receiver_id;
            $feedback->content = $request->content;

            $feedback->save();
        } else{
            return response()->json([
                'message' => 'Only Members of Project can add a Feedback'
            ], 403);
        }

        return response()->json([
            'message' => 'Feedback added successfully'
        ], 200);
    }

    public function edit(Request $request)
    {
        $request->validate([
            'feedback_id' => 'required|exists:feedbacks,id',
            'content' => 'required|max:1000'
        ]);
        $feedback = Feedback::find($request->feedback_id);

        if ($feedback->sender_id == auth()->user()->id) {

            $feedback->content = $request->content;
            $feedback->save();
            return response()->json([
                'message' => 'Feedback edited successfully'
            ], 200);

        } else {
            return response()->json([
                'message' => 'Only Creator of a Feedback can edit a Feedback'
            ], 403);
        }
    }

    public function review($project_id, Request $request)
    {
        $request->validate([
            'review' => 'required|max:1000'
        ]);
        try {
            Project::findOrFail($project_id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project doesn\'t exist!'
            ], 404);
        }
        if (count(DB::table('project_user')->where([
            ['project_id', '=' ,$project_id],
            ['user_id', '=' ,auth()->user()->id],
            ['role', '=' , 'MAINTAINER']
            ])->get()) != 0) {
                $review_project = Project::find($project_id);
                $review_project->review = $request->review;
                return $review_project;
        } else {
            return response()->json([
                'message' => 'Only Maintainers of Project can Review'
            ], 403);
        }
    }
}
