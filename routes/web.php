<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:api')->group(function (){
    Route::get('/projects/{project_id}/feedback/get/', 'FeedbackController@index');
    Route::post('/projects/{project_id}/feedback/add/', 'FeedbackController@add');
    Route::post('/projects/{project_id}/feedback/edit/', 'FeedbackController@edit');
    Route::post('/projects/{project_id}/review/', 'FeedbackController@review');
});


