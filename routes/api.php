<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//auth
Route::post('auth/login/', 'AuthController@login');
Route::post('auth/register/', 'AuthController@register');

//dashboard

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
