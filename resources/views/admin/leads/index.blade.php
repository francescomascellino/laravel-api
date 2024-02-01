@extends('layouts.admin.app')

@section('content')
    <div class="container">

        <h2 class="fs-4 text-secondary my-4">
            {{ __('Messages List for') }} {{ Auth::user()->name }}
        </h2>

        @include('admin.projects.partials.status_alert')

        {{--         <a href="{{ route('admin.projects.create') }}" class="btn btn-primary my-3 icon-link"><i
                class="fa-solid fa-file-circle-plus"></i> New Project</a> --}}

        {{--         <a href="{{ route('admin.github.fetch') }}" class="btn btn-primary my-3 icon-link"><i class="fa-brands fa-github"></i> Fetch GitHub</a> --}}

        <div class="card py-3 bg-light">

            <div class="table-responsive">
                <table class="table table-light table-striped m-0">
                    <thead>
                        <tr class="align-middle text-center">
                            <th scope="col">ID</th>
                            <th scope="col">Preview</th>
                            <th scope="col">From:</th>
                            <th scope="col" class="d-none d-sm-none d-md-table-cell">Message preview</th>
                            {{-- <th scope="col">Technologies used</th> --}}
                            {{-- <th scope="col">Quick links</th> --}}
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leads as $lead)
                            <tr class="">

                                {{-- ID CELL --}}
                                <td class="align-middle" scope="row">{{ $lead->id }}</td>

                                {{-- THUMB CELL --}}
                                @if (str_contains($lead->thumb, 'http'))
                                    <td class="text-center align-middle"><img class="img-fluid img-fluid object-fit-cover"
                                            style="height: 100px" src="{{ $lead->thumb }}" alt="{{ $lead->title }}">
                                    </td>
                                @elseif ($lead->thumb)
                                    <td class="text-center align-middle"><img class="img-fluid img-fluid object-fit-cover"
                                            style="height: 100px" src="{{ asset('storage/' . $lead->thumb) }}"></td>
                                @else
                                    <td class="text-center align-middle">N/A</td>
                                @endif

                                {{-- NAME CELL --}}
                                <td class="align-middle">{{ $lead->name }}</td>

                                {{-- MESSAGE CELL --}}
                                <td class="align-middle d-none d-sm-none d-md-table-cell">{{ $lead->message }}</td>

                                {{-- TECH CELL --}}
                                {{-- <td class="align-middle">{{ $lead->tech }}
                            </td> --}}

                                {{-- QUICK LINKS CELL --}}
                                {{-- <td class="align-middle text-center" text-center>

                                    <div class="d-inline-block d-flex">

                                        <a href="{{ $lead->github }}" target="blank" class="btn btn-dark m-1">
                                            <i class="fa-brands fa-github"></i>
                                        </a>

                                        <a href="{{ $lead->link }}" target="blank" class="btn btn-info m-1">
                                            <i class="fa-solid fa-link"></i>
                                        </a>

                                    </div>


                                    <div class="d-inline-block">

                                    </div>
                                </td> --}}

                                {{-- ACTIONS CELL --}}
                                <td class="align-middle text-center">

                                    {{-- I PROGETTI SONO COLLEGATI TRAMITE LO SLUG --}}

                                    {{-- SHOW MAIL DETAILS BUTTON --}}
                                    <div class="d-block">
                                        {{-- <a href="{{ route('admin.leads.show', $lead->slug) }}"
                                            class="btn btn-primary m-1"><i class="fa-solid fa-eye"></i></a> --}}
                                    </div>

                                    {{-- ANSWER MAIL BUTTON --}}
                                    {{-- NELLA MODALE --}}
                                    {{-- form action ="{{route.admin.lead.reply}}"" methd="POST"
                                         --}}
                                    {{-- <div class="d-block">
                                        <a href="{{ route('admin.leads.reply', $lead) }}"
                                            class="btn btn-warning m-1"><i class="fa-solid fa-pen"></i></a>
                                    </div> --}}

                                    <!-- SOFT DELETE Modal trigger button -->
                                    <div class="d-block">
                                        <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal"
                                            data-bs-target="#deleteLead{{ $lead->id }}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </div>

                                    <!-- SOFT DELETE Modal Body -->
                                    <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                    <div class="modal fade" id="deleteLead{{ $lead->id }}" tabindex="-1"
                                        data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
                                        aria-labelledby="modalTitle{{ $lead->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
                                            role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-start" id="modalTitle{{ $lead->id }}">
                                                        {{ $lead->title }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <p>This operation will move the mail from
                                                        "<strong>{{ $lead->name }}</strong>" in the Recycle Bin.</p>
                                                    <p>Are you sure?</p>
                                                </div>
                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal"><i class="fa-solid fa-ban"></i>
                                                        Cancel</button>

                                                    <form {{-- action="{{ route('admin.leads.destroy', $lead) }}" --}} method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger m-2" type="submit"><i
                                                                class="fa-regular fa-trash-can"></i> Delete (not
                                                            working)</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <td class="align-middle text-center" colspan="7">No messages to show</td>
                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>


        {{-- PAGINATION --}}
        <div class="my-3">
            {{-- {{ $projects->links('pagination::bootstrap-5') }} --}}
        </div>

        {{-- <h1>ADMIN/PROJECTS/INDEX.BLADE</h1> --}}
    </div>
@endsection
