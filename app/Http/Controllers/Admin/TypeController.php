<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $page_title = 'Types';

        $types = Type::all();

        return view('admin.types.index', compact('types', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { {
            $page_title = 'Add New Type';
            return view('admin.types.create', compact('page_title'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeRequest $request)
    {
        $valData = $request->validated();

        // INVOCHIAMO IL METODO STATICO DAL MODELLO
        $valData['slug'] = Type::generateSlug($request->name);

        $newType = Type::create($valData);

        return to_route('admin.types.index')->with('status', 'Well Done, New Type Added Succeffully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        $page_title = 'Edit Type';
        return view('admin.types.edit', compact('type', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, Type $type)
    {
        $valData = $request->validated();
        $valData['slug'] = $type->generateSlug($request->name);
        $type->update($valData);
        return to_route('admin.types.index')->with('status', 'Well Done, Element Edited Succeffully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $projects = Project::withTrashed()->has('type')->get(); // RECUPERIAMO I PROGETTI CHE HANNO UN TYPE

        // CICLIAMO I PROGETTI
        foreach ($projects as $project) {
            // QUANDO TROVIAMO UN PROGETTO IL CUI TYPE HA UN ID UGUALE A QUELLO DEL TYPE CHESTIAMO ELIMINANDO
            if ($project->type->id == $type->id) {
                // DISSOCIAMO IL TYPE
                $project->type()->dissociate();
                // NON DIMENTICHIAMO DI SALVARE
                $project->save();
            }
        }

        $type->delete();
        return to_route('admin.types.index')->with('status', 'Well Done, Element deleted Succeffully');
    }
}
