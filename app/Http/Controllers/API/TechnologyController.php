<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $technologies = Technology::OrderBy('name')->get();
        return response()->json([
            'status' => 'success',
            'result' => $technologies
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
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
    }
}
