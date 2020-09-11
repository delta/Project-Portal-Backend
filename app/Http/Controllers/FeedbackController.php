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
        if (Project::find($project_id)) {
            $feedbacks_sent = Project::find($project_id)->feedbacks()->where('sender_id', auth()->user()->id)->get();
            $feedbacks_recieved = Project::find($project_id)->feedbacks()->where('receiver_id', auth()->user()->id)->get();
        
            return response(['feedback_sent' => $feedbacks_sent, 'feedback_received' => $feedbacks_recieved]);
        } else {
            return response()->json(['message' => 'Project not Found'], 404);
        }
    }

    public function add($project_id, Request $request)
    {
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
            return response()->json(['message' => 'Only Members of Project can add a Feedback'], 403);        
            //redirect to view 
        }            
    }

    public function edit(Request $request)
    {
        $feedback = Feedback::find($request->feedback_id);

        if ($feedback->sender_id == auth()->user()->id) {

            $feedback->content = $request->content;
            $feedback->save();
            return $feedback;
            //redirect            

        } else {
            return response()->json(['message' => 'Only Creator of a Feedback can edit a Feedback'], 403);
        }        
    }

    public function review($project_id, Request $request)
    {
        if (count(DB::table('project_user')->where([
            ['project_id', '=' ,$project_id], 
            ['user_id', '=' ,auth()->user()->id],
            ['role', '=' , 'MAINTAINER']
            ])->get()) != 0) {
                $review_project = Project::find($project_id);
                $review_project->review = $request->review;
                return $review_project;
        } else {
            return response()->json(['message' => 'Only Maintainers of Project can Review'], 403);
        }            
    }
}