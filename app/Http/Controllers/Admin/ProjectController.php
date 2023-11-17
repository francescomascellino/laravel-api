<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Technology;
use App\Models\Type;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $page_title = 'Projects';

        // PAGINATION
        $projects = Project::orderByDesc('id')->paginate(4);

        $trashed_projects = Project::onlyTrashed()->get();

        return view('admin.projects.index', compact('projects', 'trashed_projects', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page_title = 'Add New Technology';
        $types = Type::all();
        $technologies = Technology::all();
        return view('admin.projects.create', compact('page_title', 'types', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $valData = $request->validated();

        // $valData['slug'] = Str::slug($request->title, '-');

        // INVOCHIAMO IL METODO STATICO DAL MODELLO
        $valData['slug'] = Project::generateSlug($request->title);

        if ($request->has('thumb')) {
            $file_path = Storage::put('thumbs', $request->thumb);
            $valData['thumb'] = $file_path;
        }

        $newProject = Project::create($valData);

        $newProject->technologies()->attach($request->technologies);

        return to_route('admin.projects.index')->with('status', 'Well Done, New Entry Added Succeffully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $page_title = 'Details';
        return view('admin.projects.show', compact('project', 'page_title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $page_title = 'Edit';

        $types = Type::all();
        $technologies = Technology::all();
        return view('admin.projects.edit', compact('project', 'page_title', 'types', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $valData = $request->validated();

        // $valData['slug'] = Str::slug($request->title, '-');

        // INVOCA IL METODO DENTRO IL MODEL
        $valData['slug'] = $project->generateSlug($request->title);

        if ($request->has('thumb')) {

            // SALVA L'IMMAGINE NEL FILESYSTEM
            $newThumb = $request->thumb;
            $path = Storage::put('thumbs', $newThumb);

            // SE IL FUMETTO HA GIA' UNA COVER NEL DB  NEL FILE SYSTEM, DEVE ESSERE ELIMINATA DATO CHE LA STIAMO SOSTITUENDO
            if (!is_Null($project->thumb) && Storage::fileExists($project->thumb)) {
                // ELIMINA LA VECCHIA PREVIEW
                Storage::delete($project->thumb);
            }

            // ASSEGNA AL VALORE DI $valData IL PERCORSO DELL'IMMAGINE NELLO STORAGE
            $valData['thumb'] = $path;
        }

        if ($request->has('technologies')) {
            $project->technologies()->sync($request->technologies); // (o valData['technologies'])
        }

        // AGGIORNA L'ENTITA' CON I VALORI DI $valData
        $project->update($valData);
        return to_route('admin.projects.show', $project->slug)->with('status', 'Well Done, Element Edited Succeffully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return to_route('admin.projects.index')->with('status', 'Well Done, Element Moved to the Recycle Bin Succeffully');
    }

    public function recycle()
    {
        $page_title = 'Recycle Bin';
        // PAGINATION
        $trashed_projects = Project::onlyTrashed()->orderByDesc('deleted_at')->paginate(4);

        return view('admin.projects.recycle', compact('trashed_projects', 'page_title'));
    }

    public function restore($id)
    {

        $project = Project::onlyTrashed()->find($id);
        $project->restore();

        return to_route('admin.projects.recycle')->with('status', 'Well Done, Element Restored Succeffully');
    }

    public function forceDelete($id)
    {

        $project = Project::onlyTrashed()->find($id);

        if (!is_Null($project->thumb)) {
            Storage::delete($project->thumb);
        }

        $project->technologies()->detach();

        $project->forceDelete();

        return to_route('admin.projects.recycle')->with('status', 'Well Done, Element Deleted Succeffully');
    }

    public function showTrashed($id)
    {
        $page_title = 'Deleted Item Details';
        $project = Project::onlyTrashed()->find($id);
        return view('admin.projects.showTrashed', compact('project', 'page_title'));
    }
}
