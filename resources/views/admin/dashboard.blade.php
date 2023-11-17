@extends('layouts.admin.app')

@section('content')
    <div class="container">

        <h2 class="fs-4 text-secondary my-4">
            {{ __('Dashboard') }}
        </h2>

        @include('admin.projects.partials.status_alert')

        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header"><i class="fa-solid fa-user"></i> {{ __('User Dashboard') }}</div>

                    <div class="card-body">
                        {{ __('You are logged in') }}, {{ Auth::user()->name }}!
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center my-3">
            <div class="col">
                <div class="card">
                    <div class="card-header"><i class="fa-solid fa-users"></i></i> {{ __('Users') }}</div>

                    <div class="card-body">
                        <p>Total Users registered on the platform: <strong>{{ $total_users }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center my-3">
            <div class="col">
                <div class="card">
                    <div class="card-header"><i class="fa-solid fa-diagram-project"></i> {{ __('Projects') }}</div>

                    <div class="card-body">
                        <p>There are a total of <strong>{{ $total_projects }}</strong> registered Projects on the platform
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center my-3">
            <div class="col">
                <div class="card">
                    <div class="card-header"><i class="fa-solid fa-tags"></i> {{ __('Project Types') }}</div>

                    <div class="card-body">
                        <p>There are a total of <strong>{{ $total_types }}</strong> registered Projects Types registered in the Database
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center my-3">
            <div class="col">
                <div class="card">
                    <div class="card-header"><i class="fa-solid fa-code"></i> {{ __('Project Technologies') }}</div>

                    <div class="card-body">
                        <p>There are a total of <strong>{{ $total_technologies }}</strong> registered Projects Technologies registered in the Database
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- <h1>ADMIN/DASHBOARD.BLADE</h1> --}}
    </div>
@endsection
