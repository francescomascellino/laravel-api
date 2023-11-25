<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ADESSO ORDINA PER DATA
        $projects = Project::with('type', 'technologies')->Orderby('created_at')->paginate(6);
        return response()->json([
            'success' => true,
            'result' => $projects
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
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
    }

    /**
     * Show the latest 3 Projects
     */
    public function latest()
    {
        $latest = Project::with('type', 'technologies')->Orderby('created_at')->take(3)->get();
        return response()->json([
            'success' => true,
            'result' => $latest
        ]);
    }
}
