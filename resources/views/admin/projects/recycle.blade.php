@extends('layouts.admin.app')

@section('content')
    <div class="container">

        <h2 class="fs-4 text-secondary my-4">
            {{ __('Deleted Projects List for') }} {{ Auth::user()->name }}
        </h2>

        @include('admin.projects.partials.status_alert')

        <div class="card py-3 bg-light">

            <div class="table-responsive">
                <table class="table table-light table-striped m-0">
                    <thead>
                        <tr class="align-middle text-center">
                            <th scope="col">ID</th>
                            <th scope="col">Preview</th>
                            <th scope="col">Title</th>
                            <th scope="col" class="d-none d-sm-none d-md-table-cell">Description</th>
                            <th scope="col">Deleted on</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trashed_projects as $project)
                            <tr class="">
                                <td class="align-middle" scope="row">{{ $project->id }}</td>

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


                                <td class="align-middle">{{ $project->title }}</td>
                                <td class="align-middle d-none d-sm-none d-md-table-cell">{{ $project->description }}</td>
                                <td class="align-middle">{{ $project->deleted_at }}</td>
                                <td class="align-middle text-center">

                                    {{-- SHOW TRASHED PROJECTS DETAILS BUTTON --}}
                                    <div class="">
                                        <a href="{{ route('admin.projects.showTrashed', $project->id) }}"
                                            class="btn btn-primary m-1"><i class="fa-solid fa-eye"></i></a>
                                    </div>

                                    {{-- RESTORE TRASHED PROJECT BUTTON --}}
                                    <div class="">
                                        <a href="{{ route('admin.projects.restore', $project->id) }}"
                                            class="btn btn-success m-1"><i class="fa-solid fa-recycle"></i></a>
                                    </div>

                                    <!-- FORCE DELETE Modal trigger button -->
                                    <div class="">
                                        <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal"
                                            data-bs-target="#deleteproject{{ $project->id }}">
                                            <i class="fa-solid fa-dumpster-fire"></i>
                                        </button>
                                    </div>


                                    <!-- FORCE DELETE Modal Body -->
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
                                                    <p>This operation will <strong class="text-danger">delete</strong> the
                                                        project "<strong>{{ $project->title }}</strong>" permanently.</p>
                                                    <p>Are you sure you want to continue?</p>
                                                </div>
                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>

                                                    <a href="{{ route('admin.projects.forceDelete', $project->id) }}"
                                                        class="btn btn-danger m-2"><strong>Delete</strong></a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Optional: Place to the bottom of scripts -->
                                    <script>
                                        const myModal = new bootstrap.Modal(document.getElementById('modalId'), options)
                                    </script>

                                </td>
                            </tr>
                        @empty
                            <td class="align-middle text-center" colspan="6">Recycle Bin is empty</td>
                        @endforelse

                    </tbody>
                </table>

            </div>

        </div>


        <div class="my-3">
            {{ $trashed_projects->links('pagination::bootstrap-5') }}
        </div>

        {{-- <h1>ADMIN/PROJECTS/RECYCLE.BLADE</h1> --}}
    </div>
@endsection
