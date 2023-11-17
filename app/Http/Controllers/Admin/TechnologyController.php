<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTechnologyRequest;
use App\Http\Requests\UpdateTechnologyRequest;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page_title = 'Technologies';

        $technologies = Technology::all();

        return view('admin.technologies.index', compact('technologies', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page_title = 'Add New technology';
        return view('admin.technologies.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTechnologyRequest $request)
    {
        $valData = $request->validated();

        // INVOCHIAMO IL METODO STATICO DAL MODELLO
        $valData['slug'] = Technology::generateSlug($request->name);

        $newTechnology = Technology::create($valData);

        return to_route('admin.technologies.index')->with('status', 'Well Done, New Technology Added Succeffully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Technology $technology)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technology $technology)
    {
        $page_title = 'Edit Technology';
        return view('admin.technologies.edit', compact('technology', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTechnologyRequest $request, Technology $technology)
    {
        $valData = $request->validated();
        $valData['slug'] = $technology->generateSlug($request->name);
        $technology->update($valData);
        return to_route('admin.technologies.index')->with('status', 'Well Done, Element Edited Succeffully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technology $technology)
    {
        $projects = Project::withTrashed();

        foreach ($projects as $project) {
            if ($project->technologies) {
                $project->technologies()->detach($technology->id);
            }
        }

        $technology->delete();
        return to_route('admin.technologies.index')->with('status', 'Well Done, Element deleted Succeffully');
    }
}
