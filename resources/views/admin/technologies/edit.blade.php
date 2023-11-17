@extends('layouts.admin.app')

@section('content')
    <div class="container">

        <h2 class="fs-4 text-secondary my-4">
            {{ __('Technology Edit Page for') }} {{ Auth::user()->name }}.
        </h2>
        <h3 class="fs-5 text-secondary">
            {{ __('Editing Technology') }} ID: {{ $technology->id }}
        </h3>

        <div class="row justify-content-center my-3">
            <div class="col">

                @include('admin.projects.partials.error_alert')

                <form action="{{ route('admin.technologies.update', $technology) }}" method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    @method('PUT')

                    <div class="mb-3">

                        <label for="name" class="form-label"><strong>Name</strong></label>

                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" aria-describedby="helpName"
                            value="{{ old('name') ? old('name') : $technology->name }}">

                        <div id="HelpName" class="form-text">
                            Your Type name must be 3-50 characters long.
                        </div>

                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <button type="submit" class="btn btn-success my-3"><i class="fa-solid fa-floppy-disk"></i>
                        Save</button>
                    <a class="btn btn-primary" href="{{ route('admin.types.index') }}"><i class="fa-solid fa-ban"></i>
                        Cancel</a>

                </form>
            </div>
        </div>

        {{-- <h1>ADMIN/TYPES/EDIT.BLADE</h1> --}}
    </div>
@endsection
