@extends('layouts.admin.app')

@section('content')
    <div class="container">

        <h2 class="fs-4 text-secondary my-4">
            {{ __('New Project Page for') }} {{ Auth::user()->name }}
        </h2>

        <div class="row justify-content-center my-3">
            <div class="col">

                @include('admin.projects.partials.error_alert')

                <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    {{-- TITLE FORM --}}
                    <div class="mb-3">

                        <label for="title" class="form-label"><strong>* Title</strong></label>

                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                            id="title" aria-describedby="helpTitle" placeholder="New Project Title"
                            value="{{ old('title') }}">

                        <div id="helpTitle" class="form-text">
                            Your title must be 3-200 characters long.
                        </div>

                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- DESCRIPTION FORM --}}
                    <div class="mb-3">

                        <label for="description" class="form-label "><strong>Description</strong></label>

                        <textarea class="form-control @error('description') is-invalid  @enderror" name="description" id="description"
                            aria-describedby="helpDescription" cols="30" rows="5" >{{ old('description') }}</textarea>

                        <div id="helpDescription" class="form-text">
                            Your description must be 3-500 characters long.
                        </div>

                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- TYPE FORM --}}
                    <div class="mb-3">
                        <label for="type_id" class="form-label"><strong>Type</strong></label>
                        <select class="form-select form-select @error('type_id') is-invalid @enderror" name="type_id"
                            aria-describedby="helpType_id" id="type_id">
                            <option selected disabled>Select a Type</option>
                            <option value="">Uncategorized</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" {{-- SE VI E' UN ERRORE E LA PAGINA VIENE RICARICATA IL CAMPO PRECEDENTEMENTE SELEZIONATO RESTA selected --}}
                                    {{ $type->id == old('type_id') ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>

                        <div id="helpType_id" class="form-text">
                            Select a Type for your Project.
                        </div>

                        @error('type_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TECH FORM --}}
                    {{--                     <div class="mb-3">

                        <label for="tech" class="form-label"><strong>Technologies Used</strong></label>

                        <input type="text" class="form-control @error('tech') is-invalid @enderror" name="tech"
                            id="tech" aria-describedby="helpTech" placeholder="Tech used creating the New Project"
                            value="{{ old('tech') }}">
                        <div id="helpTech" class="form-text">
                            Your tech list must be 3-500 characters long.
                        </div>

                        @error('tech')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div> --}}

                    {{-- TECHNOLOGIES FORM --}}
                    {{-- <div class="mb-3">
                        <label for="technologies" class="form-label"><strong>Technologies Used</strong></label>

                        <select class="form-select" multiple name="technologies[]" id="technologies">

                            <option disabled>Select Technologies used</option>

                            @foreach ($technologies as $technology)
                                <option value="{{ $technology->id }}"
                                    {{ in_array($technology->id, old('technologies', [])) ? 'selected' : '' }}>
                                    {{ $technology->name }} ID: {{ $technology->id }}</option>
                            @endforeach

                        </select>

                        @error('technologies')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div> --}}

                    <div class="my-3">
                        <label class="form-label d-block"><strong>Technologies Used:</strong></label>
                        <div class="card p-2 d-flex flex-row">
                            @foreach ($technologies as $technology)
                                <div class="form-check mx-1">

                                    {{-- VIENE DATO UN ARRAY COME NAME PER ACCETTARE SCELTE MULTIPLE --}}
                                    <input class="form-check-input @error('technologies') is-invalid @enderror"
                                        type="checkbox" id="technologies{{ $technology->id }}" name="technologies[]"
                                        aria-describedby="helpTechnology" value="{{ $technology->id }}"
                                        {{-- CONFRONTA L'ARRAY DEGLI ID DELLE TECHNOLOGIES CON QUELLO CONTENENTE I CAMPI SELEZIONATI PRECEDENTEMENTE
                                    SE VI SONO CORRISPONDENZE LI PRESELEZIONA
                                    SE L'ARRAY OLD NON ESISTE CONFRONTA UN ARRAY VUOTO [] COME FALLBACK, AUTOMATICAMENTE NON TROVANDO CORRISPONDENZE E NON SELEZIONANDO NULLA --}}
                                        {{ in_array($technology->id, old('technologies', [])) ? 'checked' : '' }}>

                                    <label class="form-check-label" for="technologies{{ $technology->id }}">{{ $technology->name }}</label>

                                </div>
                            @endforeach
                        </div>

                        <div id="helpTechnology" class="form-text">
                            Check the Technologies used in your Project.
                        </div>

                        @error('technologies')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- GITHUB LINK FORM --}}
                    <div class="mb-3">

                        <label for="github" class="form-label"><strong>GitHub Link</strong></label>

                        <input type="text" class="form-control @error('github') is-invalid @enderror" name="github"
                            id="github" aria-describedby="helpGithub"
                            placeholder="Enter your GitHub Project Repository Link" value="{{ old('github') }}">

                        <div id="helpGithub" class="form-text">
                            Enter the GitHub repository page link.
                        </div>

                        @error('github')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror



                    </div>

                    {{-- WEBSITE LINK FORM --}}
                    <div class="mb-3">

                        <label for="link" class="form-label"><strong>Project Link</strong></label>

                        <input type="text" class="form-control @error('link') is-invalid @enderror" name="link"
                            id="link" aria-describedby="helpLink" placeholder="Enter your Project Link"
                            value="{{ old('link') }}">

                        <div id="helpLink" class="form-text">
                            Enter the website link for your Project.
                        </div>

                        @error('link')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- THUMB FORM --}}
                    <div class="mb-3">

                        <label for="thumb" class="form-label"><strong>Choose a Thumbnail image file</strong></label>

                        <input type="file" class="form-control @error('thumb') is-invalid @enderror" name="thumb"
                            id="thumb" placeholder="Upload a new image file..." aria-describedby="helpThumb">

                        <div id="helpThumb" class="form-text">
                            Choose a valid image file with a max size of 500kb
                        </div>

                        @error('thumb')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <button type="submit" class="btn btn-success my-3"><i class="fa-solid fa-floppy-disk"></i>
                        Save</button>
                    <a class="btn btn-primary" href="{{ route('admin.projects.index') }}"><i
                            class="fa-solid fa-ban"></i>
                        Cancel</a>

                </form>
            </div>
        </div>

        {{-- <h1>ADMIN/PROJECTS/CREATE.BLADE</h1> --}}
    </div>
@endsection
