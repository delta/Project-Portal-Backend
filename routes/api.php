<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//auth
Route::post('auth/login/', 'AuthController@login');
Route::post('auth/register/', 'AuthController@register');

//dashboard
Route::get('dashboard/', 'DashboardController@show')->middleware('auth:api');

//project
Route::prefix('projects')->middleware('auth:api')->group(function () {
    Route::get('all/', 'ProjectController@all');
    Route::get('{project_id}/', 'ProjectController@show');
    Route::post('add/', 'ProjectController@add');
    Route::post('{project_id}/edit/', 'ProjectController@edit');
    Route::post('{project_id}/delete/', 'ProjectController@delete');
});

//filters

//feedback
Route::middleware('auth:api')->group(function (){
    Route::get('/projects/{project_id}/feedback/get/', 'FeedbackController@index');
    Route::post('/projects/{project_id}/feedback/add/', 'FeedbackController@add');
    Route::post('/projects/{project_id}/feedback/edit/', 'FeedbackController@edit');
    Route::post('/projects/{project_id}/review/', 'FeedbackController@review');
});
