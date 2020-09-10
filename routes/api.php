<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//auth
Route::post('auth/login/', 'AuthController@login');
Route::post('auth/register/', 'AuthController@register');

//dashboard

//project
Route::get('/projects/all/', 'ProjectController@all');
Route::get('/projects/{project_id}/', 'ProjectController@show');
Route::post('/projects/add/', 'ProjectController@add')->middleware('auth:api');
Route::post('/projects/{project_id}/edit/', 'ProjectController@edit')->middleware('auth:api');
Route::post('/projects/{project_id}/delete/', 'ProjectController@delete')->middleware('auth:api');

//filters

//feedback
