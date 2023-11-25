<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\GithubController;
use App\Http\Controllers\API\LeadController;
use App\Http\Controllers\ProfileController;
use App\Mail\FromLeadEmail;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $projects = Project::orderByDesc('id')->paginate(3);
    // phpinfo();
    return view('welcome', compact('projects'));
});

// ROUTES ADMIN
Route::middleware('auth', 'verified') // PER GLI UTENTI LOGGATI & VERIFICATI
    ->name('admin.') // NOME DELLE ROTTE INIZIA CON 'admin.'
    ->prefix('admin') // PREFIX DEGLI URL INIZIANO CON '/admin/'
    ->group(function () {

        // AFTER LOGIN GET REDIRECTED TO DASHBOARD
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // RESTORE TRASHED ITEM ROUTE
        Route::get('projects/restore/{id}', [ProjectController::class, 'restore'])->name('projects.restore');

        // FORCE DELETE TRASHED ITEM ROUTE
        Route::get('projects/forceDelete/{id}', [ProjectController::class, 'forceDelete'])->name('projects.forceDelete');
        Route::get('projects/recycle', [ProjectController::class, 'recycle'])->name('projects.recycle');

        // SHOW TRASHED PROJECT DETAILS ROUTE
        Route::get('projects/recycle/{id}', [ProjectController::class, 'showTrashed'])->withTrashed()->name('projects.showTrashed');

        // PROJECTS RESOURCE CONTROLLER ROUTES
        Route::resource('projects', ProjectController::class)->parameters(['projects' => 'project:slug']);

        // TYPES RESOURCE CONTROLLER ROUTES
        Route::resource('types', TypeController::class)->parameters(['types' => 'type:slug']);

        // TECHNOLOGIES RESOURCE CONTROLLER ROUTES
        Route::resource('technologies', TechnologyController::class)->parameters(['technologies' => 'technology:slug']);

        // FETCH REPOS FROM GITHUB
        Route::get('fetch-repos', [GithubController::class, 'fetchRepos'])->name('github.fetch');

        Route::get('leads', function () {
            $leads = Lead::all();

            $page_title = 'Messages';

            return view('admin.leads.index', compact('leads', 'page_title'));
        })->name('leads');

        /* Route::post('leads/{lead}', function( Request $request, Lead $lead) {
            dd($request->all());

            $lead->update($request->all());
            // CREARE CLASSE Mail LeadReplyMessage
            Mail::to($lead->email)->send(new LeadReplyMessage($lead));
        })->name('reply'); */
    });

// FETCH REPOS FROM GITHUB
// Route::get('fetch-repos', [GithubController::class, 'fetchRepos'])->name('github.fetch');

// LINK TO MAIL RECEIVED (USE TO CHECK LAYOUT)
Route::get('mailable', function () {
    $lead = Lead::find(1);
    return new FromLeadEmail($lead);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';