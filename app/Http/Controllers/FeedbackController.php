<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{

    /**
     * Get feedbacks sent or received by user of a specific project
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $projectId)
    {
        try {
            $project = Project::findOrFail($projectId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project doesn\'t exist!'
            ], 404);
        }

        $userId = $request->user()->id;

        $feedbacksSent = $project->feedbacks()->where('sender_id', $userId)->get();
        $feedbacksRecieved = $project->feedbacks()->where('receiver_id', $userId)->get();

        return response([
            'message' => "Successfully fetched feedbacks",
            'data' => [
                'feedbacks_sent' => $feedbacksSent,
                'feedbacks_received' => $feedbacksRecieved
            ]
        ]);
    }

    /**
     * Add feedback
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, $projectId)
    {
        try {
            $project = Project::findOrFail($projectId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project doesn\'t exist!'
            ], 404);
        }
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|max:1000'
        ]);

        $senderId = $request->user()->id;
        $receiverId = $request->receiver_id;

        if ($senderId === $receiverId) {
            return response()->json([
                'message' => 'You can\'t add feedback to yourself'
            ], 403);
        }
        $projectUsers = $project->users()->get();
        if (!$projectUsers->contains($senderId)) {
            return response()->json([
                'message' => 'Only project members can add feedback'
            ], 403);
        } else if (!$projectUsers->contains($receiverId)) {
            return response()->json([
                'message' => 'Only project members can receive feedback'
            ], 403);
        }

        $feedback = new Feedback;

        $feedback->project_id = $projectId;
        $feedback->sender_id = $senderId;
        $feedback->receiver_id = $receiverId;
        $feedback->content = $request->content;

        DB::transaction(function () use ($project, $feedback) {
            $feedback->save();

            $project->feedbacks()->save($feedback);
            $project->save();
        });

        if ($feedback->exists) {
            return response()->json([
                'message' => 'Feedback added successfully!'
            ], 200);
        } else {
            return responsee()->json([
                'message' => 'Feedback could not be added'
            ], 503);
        }
    }

    /**
     * Edit existing feedback
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $request->validate([
            'feedback_id' => 'required|integer|exists:feedbacks,id',
            'content' => 'required|max:1000'
        ]);

        try {
            $feedback = Feedback::find($request->feedback_id);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Feedback doesn\'t exist'
            ], 404);
        }

        if ($feedback->sender_id != $request->user()->id) {
            return response()->json([
                'message' => 'Only Creator of a Feedback can edit a Feedback'
            ], 403);
        }

        $feedback->content = $request->content;

        \DB::transaction(function () use ($feedback) {
            $feedback->save();
        });

        if ($feedback->content == $request->content) {
            return response()->json([
                'message' => 'Feedback edited successfully!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Feedback could not be edited'
            ], 503);
        }
    }

    /**
     * Add review
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function review(Request $request, $projectId)
    {
        $request->validate([
            'review' => 'required|max:1000'
        ]);

        try {
            $project = Project::findOrFail($projectId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project doesn\'t exist!'
            ], 404);
        }

        $user = $request->user();

        if (!$project->users()
            ->where(function ($query) {
                $query->where('project_user.role', 'AUTHOR')
                    ->orWhere('project_user.role', 'MAINTAINER');
            })->get()
            ->contains($user->id)) {
            return response()->json([
                'message' => 'Only authors or maintainers are allowed to add reviews'
            ], 403);
        }

        $project->review = $request->review;

        DB::transaction(function () use ($project) {
            $project->save();
        });

        if ($project->review == $request->review) {
            return response()->json([
                'message' => 'Review added successfully!'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Review could not be added'
            ], 503);
        }
    }
}
