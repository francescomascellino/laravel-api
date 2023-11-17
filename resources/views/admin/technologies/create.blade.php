@extends('layouts.admin.app')

@section('content')
    <div class="container">

        <h2 class="fs-4 text-secondary my-4">
            {{ __('Create New Technology Page for') }} {{ Auth::user()->name }}.
        </h2>

        <div class="row justify-content-center my-3">
            <div class="col">

                @include('admin.projects.partials.error_alert')

                <form action="{{ route('admin.technologies.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="mb-3">

                        <label for="name" class="form-label"><strong>* Name</strong></label>

                        <input type="text" class="form-control @error('name') is-invalid"  @enderror name="name"
                            id="name" aria-describedby="HelpName" placeholder="New Technology name"
                            value="{{ old('name') }}">
                        <div id="HelpName" class="form-text">
                            Your Type name must be 3-50 characters long.
                        </div>

                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                    </div>

                    <button type="submit" class="btn btn-success my-3"><i class="fa-solid fa-floppy-disk"></i>
                        Save</button>
                    <a class="btn btn-primary" href="{{ route('admin.technologies.index') }}"><i class="fa-solid fa-ban"></i>
                        Cancel</a>

                </form>
            </div>
        </div>

        {{-- <h1>ADMIN/TECHNOLOGIES/CREATE.BLADE</h1> --}}
    </div>
@endsection
