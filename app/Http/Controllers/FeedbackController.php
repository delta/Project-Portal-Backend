<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Feedback;
use App\Models\ProjectUser;
use SebastianBergmann\CodeCoverage\Report\Xml\Project as XmlProject;

class FeedbackController extends Controller
{
    public function index($project_id)
    {
        $feedbacks_sent = Project::find($project_id)->feedbacks()->where('sender_id', auth()->user()->id)->get();
        $feedbacks_recieved = Project::find($project_id)->feedbacks()->where('receiver_id', auth()->user()->id)->get();
        
        return response(['feedback_sent' => $feedbacks_sent, 'feedback_received' => $feedbacks_recieved]);
    }

    public function add($project_id, Request $request)
    {
        $projects =  ProjectUser::where('project_id', $project_id)->get();
        foreach ($projects as $project) {
            
            if ($project->user_id == auth()->user()->id) {
                $feedback = new Feedback;

                $feedback->project_id = $project_id;
                $feedback->sender_id = auth()->user()->id;
                $feedback->receiver_id = $request->receiver_id;
                $feedback->content = $request->content;
                
                $feedback->save();
                return $feedback;                
                //redirect 
            }
        }

        return response(['error_message'=> "you are not a part of this group!!!"]);        
        //redirect to view 
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
            return response(['Reject_Message' => 'access denied' ]);
        }        
    }

    public function review($project_id, Request $request)
    {
        $projects =  ProjectUser::where('project_id', $project_id)->get();
        foreach ($projects as $project) {
            
            if ($project->user_id == auth()->user()->id) {
                if ($project->role == 'MAINTAINER') {
                    $review_project = Project::find($project_id);
                    $review_project->review = $request->review;
                    return $review_project;
                }             
            }
        }

        return response(['error_message' => 'you cannot review this project!!']);
    }
}
