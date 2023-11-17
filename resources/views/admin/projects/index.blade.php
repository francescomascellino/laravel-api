@extends('layouts.admin.app')

@section('content')
    <div class="container">

        <h2 class="fs-4 text-secondary my-4">
            {{ __('Project List for') }} {{ Auth::user()->name }}
        </h2>

        @include('admin.projects.partials.status_alert')

        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary my-3"><i
                class="fa-solid fa-file-circle-plus"></i> New Project</a>

        <div class="card py-3 bg-light">

            <div class="table-responsive">
                <table class="table table-light table-striped m-0">
                    <thead>
                        <tr class="align-middle text-center">
                            <th scope="col">ID</th>
                            <th scope="col">Preview</th>
                            <th scope="col">Title</th>
                            <th scope="col" class="d-none d-sm-none d-md-table-cell">Description</th>
                            {{-- <th scope="col">Technologies used</th> --}}
                            <th scope="col">Quick links</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr class="">

                                {{-- ID CELL --}}
                                <td class="align-middle" scope="row">{{ $project->id }}</td>

                                {{-- THUMB CELL --}}
                                @if (str_contains($project->thumb, 'http'))
                                    <td class="text-center align-middle"><img class="img-fluid img-fluid object-fit-cover"
                                            style="height: 100px" src="{{ $project->thumb }}" alt="{{ $project->title }}">
                                    </td>
                                @elseif ($project->thumb)
                                    <td class="text-center align-middle"><img class="img-fluid img-fluid object-fit-cover"
                                            style="height: 100px" src="{{ asset('storage/' . $project->thumb) }}"></td>
                                @else
                                    <td class="text-center align-middle">N/A</td>
                                @endif

                                {{-- TITLE CELL --}}
                                <td class="align-middle">{{ $project->title }}</td>

                                {{-- DESCRIPTION CELL --}}
                                <td class="align-middle d-none d-sm-none d-md-table-cell">{{ $project->description }}</td>

                                {{-- TECH CELL --}}
                                {{-- <td class="align-middle">{{ $project->tech }}
                            </td> --}}

                                {{-- QUICK LINKS CELL --}}
                                <td class="align-middle text-center" text-center>
                                    <div class="d-inline-block d-flex">

                                        <a href="{{ $project->github }}" target="blank" class="btn btn-dark m-1">
                                            <i class="fa-brands fa-github"></i>
                                        </a>

                                        <a href="{{ $project->link }}" target="blank" class="btn btn-info m-1">
                                            <i class="fa-solid fa-link"></i>
                                        </a>
                                    </div>


                                    <div class="d-inline-block">

                                    </div>
                                </td>

                                {{-- ACTIONS CELL --}}
                                <td class="align-middle text-center">

                                    {{-- I PROGETTI SONO COLLEGATI TRAMITE LO SLUG --}}

                                    {{-- SHOW PROJECT DETAILS BUTTON --}}
                                    <div class="d-block">
                                        <a href="{{ route('admin.projects.show', $project->slug) }}"
                                            class="btn btn-primary m-1"><i class="fa-solid fa-eye"></i></a>
                                    </div>

                                    {{-- EDIT PROJECT BUTTON --}}
                                    <div class="d-block">
                                        <a href="{{ route('admin.projects.edit', $project->slug) }}"
                                            class="btn btn-warning m-1"><i class="fa-solid fa-pen"></i></a>
                                    </div>

                                    <!-- SOFT DELETE Modal trigger button -->
                                    <div class="d-block">
                                        <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal"
                                            data-bs-target="#deleteproject{{ $project->id }}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>

                                    <!-- SOFT DELETE Modal Body -->
                                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                    <div class="modal fade" id="deleteproject{{ $project->id }}" tabindex="-1"
                                        data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
                                        aria-labelledby="modalTitle{{ $project->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
                                            role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-start" id="modalTitle{{ $project->id }}">
                                                        {{ $project->title }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <p>This operation will move the project
                                                        "<strong>{{ $project->title }}</strong>" in the Recycle Bin.</p>
                                                    <p>Are you sure?</p>
                                                </div>
                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal"><i class="fa-solid fa-ban"></i>
                                                        Cancel</button>

                                                    <form action="{{ route('admin.projects.destroy', $project) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger m-2" type="submit"><i
                                                                class="fa-regular fa-trash-can"></i> Delete</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <td class="align-middle text-center" colspan="7">No Projects to show</td>
                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>


        {{-- PAGINATION --}}
        <div class="my-3">
            {{ $projects->links('pagination::bootstrap-5') }}
        </div>

        {{-- <h1>ADMIN/PROJECTS/INDEX.BLADE</h1> --}}
    </div>
@endsection
