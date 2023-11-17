@extends('layouts.admin.app')

@section('content')
    <div class="container">

        <h2 class="fs-4 text-secondary my-4">
            {{ __('Project Details for') }} {{ Auth::user()->name }}.
        </h2>
        <h3 class="fs-5 text-secondary">
            {{ __('Showing Project') }} ID: {{ $project->id }}
        </h3>

        @include('admin.projects.partials.status_alert')

        <div class="row justify-content-center my-3">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $project->title }}</h5>
                    </div>

                    @if (str_contains($project->thumb, 'http'))
                        <img class="img-fluid object-fit-cover" style="height: 400px" src="{{ $project->thumb }}"
                            alt="{{ $project->title }}">
                    @elseif ($project->thumb)
                        <img class="img-fluid object-fit-cover" style="height: 400px"
                            src="{{ asset('storage/' . $project->thumb) }}">
                    @else
                    <div class="d-flex flex-column justify-content-center bg-body-secondary" style="height: 400px">
                        <h1 class="text-center"><i class="fa-regular fa-face-grin-beam-sweat"></i></h1>
                        <h1 class="text-center">No avaiable preview</h1>
                        <h1 class="text-center">for this project</h1>
                    </div>
                    @endif

                    <div class="card-body">
                        <p><strong>Description: </strong>{{ $project->description }}</p>
                        <p><strong>Type: </strong>{{ isset($project->type->name) ? $project->type->name : 'Uncategorized' }}
                        </p>

                        <p><strong>Technologies used:</strong></p>

                        <div class="d-flex">
                            <ul class="d-flex gap-2 list-unstyled">
                                @forelse ($project->technologies as $technology)
                                    <li class="badge bg-success">
                                        <i class="fa-solid fa-code"></i> {{ $technology->name }}
                                    </li>
                                @empty
                                    <li class="badge bg-secondary"><i class="fa-regular fa-file"></i> None/Others</li>
                                @endforelse
                            </ul>
                        </div>

                        <p><i class="fa-brands fa-github"></i> <a href="{{ $project->github }}"
                                class="text-decoration-none text-black" target="blank">{{ $project->github }}</a>
                        </p>

                        <p><i class="fa-solid fa-link"></i> <a href="{{ $project->link }}"
                                class="text-decoration-none text-black" target="blank">{{ $project->link }}</a></p>
                    </div>
                </div>
            </div>

        </div>

        <a href="{{ route('admin.projects.recycle') }}" class="btn btn-primary my-3"><i
                class="fa-solid fa-arrow-rotate-left"></i> Back</a>

        {{-- <h1>ADMIN/PROJECTS/SHOWTRASHED.BLADE</h1> --}}
    </div>
@endsection
