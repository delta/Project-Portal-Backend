<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Stack;
use App\Models\Type;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Get all projects
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        $userId = $request->user()->id;

        return response()->json([
            'message' => 'Success!',
            'data' => [
                'projects' => Project::with([
                    'feedbacks' => function ($feedback) use ($userId) {
                        $feedback->where('sender_id', $userId)
                            ->orWhere('receiver_id', $userId);
                    },
                    'stacks',
                    'status',
                    'users',
                    'type'
                ])->get()
            ]
        ], 200);
    }

    /**
     * Show project of specific id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $projectId)
    {
        $userId = $request->user()->id;

        try {
            Project::findOrFail($projectId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project doesn\'t exist!'
            ], 404);
        }
        return response()->json([
            'message' => 'Success!',
            'data' => [
                'project' => Project::where('id', $projectId)->with([
                    'feedbacks' => function ($feedback) use ($userId) {
                        $feedback->where('sender_id', $userId)
                            ->orWhere('receiver_id', $userId);
                    },
                    'stacks',
                    'status',
                    'users',
                    'type'
                ])->get()
            ]
        ], 200);
    }

    /**
     * Store new project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'deadline' => 'nullable|date|date_format:Y-m-d H:i:s|after_or_equal:today',
            'max_member_count' => 'required|integer',
            'repo_link' => 'required|unique:projects,repo_link|url',
            'review' => 'nullable',
            'status' => 'required|exists:statuses,id',
            'type' => 'required|exists:types,id',
            'users' => 'nullable|array',
            'users.*.id' => 'required|exists:users,id',
            'users.*.role' => 'required|in:AUTHOR,MAINTAINER,DEVELOPER',
            'stacks' => 'required|array|min:1',
            'stacks.*' => 'exists:stacks,id'
        ]);

        $project = new Project;
        $project->name = $data['name'];
        $project->description = $data['description'];
        if (isset($data['deadline'])) {
            $project->deadline = $data['deadline'];
        }
        if (isset($data['review'])) {
            $project->review = $data['review'];
        }
        $project->max_member_count = $data['max_member_count'];
        $project->repo_link = $data['repo_link'];

        \DB::transaction(function () use ($project, $user, $data) {

            // Associate one-to-one relations
            $project->type()->associate($data['type']);
            $project->status()->associate($data['status']);
            $project->save();

            // Save many-to-many relations
            $project->users()->syncWithoutDetaching(
                $user,
                ['role' => 'AUTHOR']
            );
            if (isset($data['users'])) {
                foreach ($data['users'] as $projectUser) {
                    $project->users()->syncWithoutDetaching([
                        (int) $projectUser['id'] =>
                            ['role' => $projectUser['role']]
                    ]);
                }
            }
            $project->stacks()->syncWithoutDetaching($data['stacks']);
            $project->save();
        });

        return response()->json([
            'message' => 'Project created successfully!',
            'data' => [
                'project_id' => $project->id
            ]
        ], 200);
    }

    /**
     * Edit the specified project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $projectId)
    {
        $user = $request->user();
        try {
            $project = Project::findOrFail($projectId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project doesn\'t exist!'
            ], 404);
        }

        if (!$project->users()
            ->where(function ($query) {
                $query->where('project_user.role', 'AUTHOR')
                    ->orWhere('project_user.role', 'MAINTAINER');
            })->get()
            ->contains($user->id)) {
            return response()->json([
                'message' => 'You are not allowed to edit this project!'
            ], 403);
        }

        $data = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:5000',
            'deadline' => 'nullable|date|date_format:Y-m-d H:i:s|after_or_equal:today',
            'max_member_count' => 'required|integer|min:1|max:100',
            'repo_link' => 'required|unique:projects,repo_link,' . $projectId,
            'review' => 'nullable|max:1000',
            'status' => 'required|exists:statuses,id',
            'type' => 'required|exists:types,id',
            'users' => 'nullable|array',
            'users.*.id' => 'required|exists:users,id',
            'users.*.role' => 'required|in:AUTHOR,MAINTAINER,DEVELOPER',
            'stacks' => 'required|array|min:1',
            'stacks.*' => 'exists:stacks,id'
        ]);

        $project->name = $data['name'];
        $project->description = $data['description'];
        if (isset($data['deadline'])) {
            $project->deadline = $data['deadline'];
        }
        if (isset($data['review'])) {
            $project->review = $data['review'];
        }
        $project->max_member_count = $data['max_member_count'];
        $project->repo_link = $data['repo_link'];

        \DB::transaction(function () use ($project, $user, $data) {

            // Associate one-to-one relations
            $project->type()->associate($data['type']);
            $project->status()->associate($data['status']);
            $project->save();

            // Save many-to-many relations
            $project->users()->syncWithoutDetaching(
                $user,
                ['role' => 'AUTHOR']
            );
            if (isset($data['users'])) {
                foreach ($data['users'] as $projectUser) {
                    $project->users()->syncWithoutDetaching([
                        $projectUser['id'] =>
                            ['role' => $projectUser['role']]
                    ]);
                }
            }
            $project->stacks()->syncWithoutDetaching($data['stacks']);
            $project->save();
        });

        return response()->json([
            'message' => 'Project edited successfully!'
        ], 200);
    }

    /**
     * Remove the specified project from storage.
     *
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $projectId)
    {
        $user = $request->user();
        try {
            $project = Project::findOrFail($projectId);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Project doesn\'t exist!'
            ], 404);
        }

        if (!$project->users()
            ->wherePivot('role', 'AUTHOR')
            ->get()
            ->contains($user->id)) {
            return response()->json([
                'message' => 'You are not allowed to delete this project!'
            ], 403);
        }

        \DB::transaction(function () use ($project, $projectId) {

            $project->users()->each(function ($user) use ($projectId) {
                $user->projects()->syncWithoutDetaching([
                    $projectId =>
                        ['deleted_at' => \DB::raw('NOW()')]
                ]);
            });
            $project->stacks()->each(function ($stack) use ($projectId) {
                $stack->projects()->syncWithoutDetaching([
                    $projectId =>
                        ['deleted_at' => \DB::raw('NOW()')]
                ]);
            });

            $project->delete();

        });

        return response()->json([
            'message' => 'Project successfully deleted'
        ], 200);

    }

    public function user_filter($user_id)
    {
        $user_project = DB::table('project_user')->where([
            ['user_id','=' ,$user_id]
        ])->get();
    
        $projects = [];

        if (User::find($user_id)) {
            if (count($user_project) != 0) {
                foreach ($user_project as $single_project) {
                    array_push($projects, Project::find($single_project->project_id)); 
                }
                return $projects;
            }else{
                return response()->json(['message' => 'User has no Project involvement'], 404);
            }
        }else{
            return response()->json(['message' => 'User not Found'], 404);
        }
    }

    public function stack_filter($stack_id)
    {
        $stack_project = DB::table('project_stack')->where([
            ['stack_id', '=' , $stack_id]
        ])->get();
        
        $projects = [];

        if (Stack::find($stack_id)) {
            if (count($stack_project) != 0) {
                foreach ($stack_project as $single_project) {
                    array_push($projects, Project::find($single_project->project_id)); 
                }
                return $projects;
            }else{
                return response()->json(['message' => 'No Project of this Stack'], 404);
            }
        } else {
            return response()->json(['message' => 'Stack not Found'], 404);
        }
    }

    public function type_filter($type_id)
    {
        $projects = DB::table('projects')->where([
            ['type_id', '=' , $type_id]
        ])->get();
        

        if (Type::find($type_id)) {
            if (count($projects) != 0) {
                return $projects;
            }else{
                return response()->json(['message' => 'No Project of this Type'], 404);
            }
        } else {
            return response()->json(['message' => 'Type not Found'], 404);
        }
    }
}
