@extends('layouts.app')
@section('content')
    <div class="jumbotron p-5 mb-4 bg-dark rounded-3">
        <div class="container py-5">
            <h1 class="display-5 fw-bold text-light">
                Welcome to the Portfolio with authentication test page! Now With Table Relationships!
            </h1>
        </div>
    </div>

    <div class="content">
        <div class="container">

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 my-3">
                @foreach ($projects as $project)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-header" style="height: 4rem">
                                <h5>{{ $project->title }}</h5>
                            </div>

                            @if (str_contains($project->thumb, 'http'))
                                <img class="img-fluid object-fit-cover" style="height: 300px" src="{{ $project->thumb }}"
                                    alt="{{ $project->title }}">
                            @elseif ($project->thumb)
                                <img class="img-fluid object-fit-cover" style="height: 300px"
                                    src="{{ asset('storage/' . $project->thumb) }}">
                            @else
                                <div class="d-flex flex-column justify-content-center bg-body-secondary"
                                    style="height: 300px">
                                    <h1 class="text-center"><i class="fa-regular fa-face-grin-beam-sweat"></i></h1>
                                    <h1 class="text-center">No avaiable preview</h1>
                                    <h1 class="text-center">for this project</h1>
                                </div>
                            @endif

                            <div class="card-body">

                                <p><strong>Description: </strong>{{ $project->description }}</p>

                                <p><strong>Type:
                                    </strong>{{ isset($project->type->name) ? $project->type->name : 'Uncategorized' }}</p>

                                <p><strong>Technologies used: </strong>{{ $project->tech }}</p>

                                <div class="d-flex">
                                    <ul class="d-flex gap-2 list-unstyled">
                                        @forelse ($project->technologies as $technology)
                                            <li class="badge bg-success">
                                                <i class="fa-solid fa-code"></i> {{ $technology->name }}
                                            </li>
                                        @empty
                                            <li class="badge bg-secondary"><i class="fa-regular fa-file"></i> None/Others
                                            </li>
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
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="my-3">
                {{ $projects->links('pagination::bootstrap-5') }}
            </div>

            {{-- <h1>welcome.blade</h1> --}}
        </div>
    </div>
@endsection
