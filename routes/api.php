<?php

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ROUTE TUTTI I PROGETTI (index())
Route::get('projects', function () {
    $projects = Project::with('type', 'technologies')->OrderbyDesc('id')->paginate(6);
    return response()->json([
        'status' => 'success',
        'result' => $projects
    ]);
});

// ROUTE ULTIMI 3 PROGETTI (latest())
Route::get('projects/latest', function () {
    $latest = Project::with('type', 'technologies')->OrderbyDesc('id')->take(3)->get();
    return response()->json([
        'status' => 'success',
        'result' => $latest
    ]);
});

// ROUTE SINGOLO PROGETTO (show())
Route::get('projects/{project:slug}', function ($slug) {

    $project = Project::with('type', 'technologies')->where('slug', $slug)->first();

    if ($project) {
        return response()->json([
            'success' => 'true',
            'result' => $project
        ]);
    } else {
        return response()->json([
            'success' => 'false',
            'result' => 'Project not found'
        ]);
    }
});
