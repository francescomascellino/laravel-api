@extends('layouts.admin.app')

@section('content')
    <div class="container">

        <h2 class="fs-4 text-secondary my-4">
            {{ __('Project Types List for') }} {{ Auth::user()->name }}
        </h2>

        @include('admin.projects.partials.status_alert')

        <a href="{{ route('admin.types.create') }}" class="btn btn-primary my-3"><i class="fa-solid fa-circle-plus"></i> Add new Type</a>

        <div class="table-responsive">
            <table class="table table-light table-striped">
                <thead>
                    <tr class="align-middle text-center">
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($types as $type)
                        <tr class="">
                            <td class="align-middle text-center" scope="row">{{ $type->id }}</td>


                            <td class="align-middle text-center">{{ $type->name }}</td>

                            {{-- ACTIONS CELL --}}
                            <td class="align-middle text-center">

                                {{-- I PROGETTI SONO COLLEGATI TRAMITE LO SLUG --}}

                                {{-- SHOW type DETAILS BUTTON --}}
{{--                                 <div class="d-inline-block">
                                    <a href="{{ route('admin.types.show', $type->slug) }}"
                                        class="btn btn-primary m-1"><i class="fa-solid fa-eye"></i></a>
                                </div> --}}

                                {{-- EDIT type BUTTON --}}
                                <div class="d-inline-block">
                                    <a href="{{ route('admin.types.edit', $type->slug) }}"
                                        class="btn btn-warning m-1"><i class="fa-solid fa-pen"></i></a>
                                </div>

                                <!-- SOFT DELETE Modal trigger button -->
                                <div class="d-inline-block">
                                    <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal"
                                        data-bs-target="#deletetype{{ $type->id }}">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>

                                <!-- SOFT DELETE Modal Body -->
                                <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                <div class="modal fade" id="deletetype{{ $type->id }}" tabindex="-1"
                                    data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
                                    aria-labelledby="modalTitle{{ $type->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-start" id="modalTitle{{ $type->id }}">
                                                    {{ $type->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <p>This operation will <strong class="text-danger">delete</strong> type
                                                    "<strong>{{ $type->name }}</strong>" permanently.</p>
                                                <p>Are you sure?</p>
                                            </div>
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                                        class="fa-solid fa-ban"></i> Cancel</button>

                                                <form action="{{ route('admin.types.destroy', $type) }}"
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
                        <td class="align-middle text-center" colspan="7">No Types to show</td>
                    @endforelse

                </tbody>
            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="my-3">
            {{-- {{ $types->links('pagination::bootstrap-5') }} --}}
        </div>

        {{-- <h1>ADMIN/TYPES/INDEX.BLADE</h1> --}}
    </div>
@endsection
