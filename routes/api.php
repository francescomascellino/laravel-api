<?php

use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TechnologyController;
use App\Http\Controllers\API\TypeController;
use App\Models\Project;
use App\Models\Technology;
use App\Models\Type;
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
/* Route::get('projects', function () {
    $projects = Project::with('type', 'technologies')->OrderbyDesc('id')->paginate(6);
    return response()->json([
        'success' => true,
        'result' => $projects
    ]);
}); */
Route::get('projects', [ProjectController::class, 'index']);

// ROUTE ULTIMI 3 PROGETTI (latest())
/* Route::get('projects/latest', function () {
    $latest = Project::with('type', 'technologies')->OrderbyDesc('id')->take(3)->get();
    return response()->json([
        'success' => true,
        'result' => $latest
    ]);
}); */
Route::get('projects/latest', [ProjectController::class, 'latest']);

// ROUTE SINGOLO PROGETTO (show())
/* Route::get('projects/{project:slug}', function ($slug) {

    $project = Project::with('type', 'technologies')->where('slug', $slug)->first();

    if ($project) {
        return response()->json([
            'success' => true,
            'result' => $project
        ]);
    } else {
        return response()->json([
            'success' => false,
            'result' => 'Project not found'
        ]);
    }
}); */
Route::get('projects/{project:slug}', [ProjectController::class, 'show']);

// ROUTE TUTTI I TYPES (index())
/* Route::get('types', function () {
    $types = Type::OrderBy('name')->get();
    return response()->json([
        'success' => true,
        'result' => $types
    ]);
}); */
Route::get('types', [TypeController::class, 'index']);

// ROUTE SINGOLO TYPE CON PROGETTI E TECHNOLOGIES (show())
/* Route::get('types/{type:slug}', function ($slug) {

    $type = Type::with('projects', 'projects.technologies', 'projects.type')->where('slug', $slug)->first();

    if ($type) {
        return response()->json([
            'success' => true,
            'result' => $type
        ]);
    } else {
        return response()->json([
            'success' => false,
            'result' => 'Type not found'
        ]);
    }
}); */
Route::get('types/{type:slug}', [TypeController::class, 'show']);

// ROUTE TUTTE LE TECHNOLOGIES (index())
/* Route::get('technologies', function () {
    $technologies = Technology::OrderBy('name')->get();
    return response()->json([
        'status' => 'success',
        'result' => $technologies
    ]);
}); */
Route::get('technologies', [TechnologyController::class, 'index']);

// ROUTE SINGOLA TECHNOLOGY CON PROGETTI E TYPE (show())
/* Route::get('technologies/{technology:slug}', function ($slug) {

    $technology = Technology::with('project', 'project.technologies', 'project.type')->where('slug', $slug)->first();

    if ($technology) {
        return response()->json([
            'success' => true,
            'result' => $technology
        ]);
    } else {
        return response()->json([
            'success' => false,
            'result' => 'Technology not found'
        ]);
    }
}); */
Route::get('technologies/{technology:slug}', [TechnologyController::class, 'show']);

Route::post('/lead', [LeadController::class, 'store']);
