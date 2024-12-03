<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DoubleEliminationController;
use App\Http\Controllers\FacilitatorController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\FreeForAllController;
use App\Http\Controllers\OnePalakasanController;
use App\Http\Controllers\PalakasanController;
use App\Http\Controllers\PalakasanHistoryController;
use App\Http\Controllers\PalakasanSportsController;
use App\Http\Controllers\PalakasanTeamController;
use App\Http\Controllers\PointsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoundRobinController;
use App\Http\Controllers\SingleEliminationController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPalakasanController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\SubAdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CHSDashboardController;
use App\Models\AssignedSports;
use App\Models\Student;
use App\Models\StudentAccount;
use App\Http\Controllers\PlayerSportController;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Round;
use App\Http\Controllers\SportsLandingController;
use Illuminate\Http\Request;

// Login routes (accessible without authentication)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Change Password Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password/{id}', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('password.change');
});

Route::get('/create-admin', function () {
    return Inertia::render('Auth/CreateAdmin');
})->name('create-admin');

Route::get('/create-admin/step-2', [AdminController::class, 'showStep2'])->name('create-admin.step2');
Route::post('/register-admin/create-student', [AdminController::class, 'createStudent']);
Route::post('/register-admin/create-account', [AdminController::class, 'createAccount']);
Route::delete('/register-admin/cancel-student/{id}', [AdminController::class, 'cancelStudent'])->name('cancel-student');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/search-user', [ForgotPasswordController::class, 'searchUser'])->name('password.search');
Route::get('/reset-password/{id}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Activity logs route
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::post('/admin/change-password', [DashboardController::class, 'changePassword'])->name('admin.change-password');
    Route::get('/account-settings', [DashboardController::class, 'accountSettings'])->name('admin.account-settings');
});

// Sports Landing Page routes
Route::get('/sports', [SportsLandingController::class, 'sports'])->name('sports.index');
Route::get('/home-sportview/{sport}', [SportsLandingController::class, 'sportsIndex'])->name('home-sportview.index');

// Admin routes
Route::middleware(['web', 'auth', 'adminMiddleware'])->group(function () {
    Route::get('/admin',[DashboardController::class, 'index'])->name('admin.show');
    Route::resource('college', CollegeController::class);
    Route::resource('venue', VenueController::class);
    Route::resource('sport', SportController::class);
    Route::resource('points', PointsController::class);
    Route::resource('account', AccountController::class);
    Route::resource('palakasan/details',PalakasanController::class);
    Route::post('/palakasan/details', [PalakasanController::class, 'store'])->name('palakasan.store');
    Route::resource('palakasan/team', PalakasanTeamController::class);
    Route::resource('palakasan/sportselection', PalakasanSportsController::class);

    //sports setup
    Route::get('details/sportview/{sport}', function ($sport) {
        $sportModel = AssignedSports::findOrFail($sport);
    
        switch ($sportModel->setup) {
            case 'Single Elimination':
                return app(SingleEliminationController::class)->index($sportModel);
            case 'Double Elimination':
                return app(DoubleEliminationController::class)->index($sportModel);
            case 'Free for All':
                return app(FreeForAllController::class)->index($sportModel);
            case 'Round Robin':
                return app(RoundRobinController::class)->index($sportModel);
            default:
                abort(404, 'Invalid sport setup');
        }
    })->name('sportview.index');

    Route::get('details', [OnePalakasanController::class, 'details'])->name('palakasan.details');
    Route::post('detail/store', [OnePalakasanController::class, 'store_palakasan'])->name('palakasan.store');
    Route::post('sport/store', [OnePalakasanController::class, 'store_sports'])->name('palakasanSport.store');
    Route::post('team/store', [OnePalakasanController::class, 'store_teams'])->name('team.store');
    Route::post('sport/delete', [OnePalakasanController::class, 'deleteSports'])->name('onepalakasan.delete-sports');
    Route::put('/palakasan/{id}/update-status', [OnePalakasanController::class, 'updatePalakasanStatus'])->name('palakasan.updateStatus');
    Route::patch('/assigned-sports/{id}/update-status', [OnePalakasanController::class, 'updateSportStatus'])->name('assigned-sports.update-status');
    Route::post('/match-result', [OnePalakasanController::class, 'storeMatchResult'])->name('match.result.store');
    Route::post('/palakasan/{palakasan}/continue', [OnePalakasanController::class, 'continuePalakasan'])->name('palakasan.continue');

    Route::resource('student', StudentController::class);
    Route::post('student/import', [StudentController::class, 'import'])->name('student.import');

    Route::get('details/palakasan-history/{palakasan}', [PalakasanHistoryController::class, 'show'])->name('palakasanHistory.show');
    Route::post('/matches', [SingleEliminationController::class, 'store_single_elimination'])->name('matchesSingle.store');
    Route::post('/matchesDE', [DoubleEliminationController::class, 'store'])->name('matches.store');
    Route::post('/sport-variations', [FreeForAllController::class, 'store'])->name('sport-variations.store');
    Route::post('/generate-teams', [FreeForAllController::class, 'generateTeams'])->name('generate-teams');
    Route::put('/matches/update-date-time', [SingleEliminationController::class, 'updateDateTime'])->name('matches.updateDateTime');
    Route::post('/resultsSE', [SingleEliminationController::class, 'storeResult'])->name('results.store');
    Route::post('/resultsDE', [DoubleEliminationController::class, 'storeResult'])->name('results.store');

    Route::post('/round-robin/store', [RoundRobinController::class, 'store_round_robin'])->name('round-robin.store');
    Route::post('/matches/update-date-time', [RoundRobinController::class, 'updateDateTime'])->name('matches.updateDateTimeRR');
    Route::post('/results/store', [RoundRobinController::class, 'storeResult'])->name('resultsRR.store');
    Route::patch('/sport-variations/{sportVariation}/update-time', [FreeForAllController::class, 'updateTime'])->name('sport-variations.update-time');
    Route::patch('/assigned-sports/{id}/update-status', [FreeForAllController::class, 'updateStatus'])->name('assigned-sports.update-status');
    Route::patch('/sport-variations/{sportVariation}/ranks', [FreeForAllController::class, 'updateRanks'])->name('sport-variations.update-ranks');
    Route::post('/overall-results', [DoubleEliminationController::class, 'storeOverallResults'])->name('overall-results.store');
    Route::post('/palakasan/{id}/emergency-cancel', [OnePalakasanController::class, 'emergencyCancel'])->name('palakasan.emergency-cancel');
    Route::post('/palakasan/conclude', [OnePalakasanController::class, 'concludePalakasan'])->name('palakasan.conclude');
});



// Facilitator routes
Route::middleware(['web', 'auth', 'facilitatorMiddleware'])->group(function () {
    Route::get('/facidashboard/{id}', [FacilitatorController::class, 'index'])->name('facilitator.show');
    Route::get('sportview/{sport}/{facilitator}', function ($sport, $facilitator) {
        $sportModel = AssignedSports::findOrFail($sport);

        switch ($sportModel->setup) {
            case 'Single Elimination':
                return app(SingleEliminationController::class)->facilitatorIndex($sportModel, $facilitator);
            case 'Double Elimination':
                return app(DoubleEliminationController::class)->facilitatorIndex($sportModel, $facilitator);
            case 'Free for All':
                return app(FreeForAllController::class)->facilitatorIndex($sportModel, $facilitator);
            case 'Round Robin':
                return app(RoundRobinController::class)->facilitatorIndex($sportModel, $facilitator);
            default:
                abort(404, 'Invalid sport setup');
        }
    })->name('facilitator-sportview.index');
});

// Committee Head routes
Route::middleware(['web', 'auth', 'comHeadMiddleware'])->group(function () {
    Route::get('/cshdashboard/{id}', [CHSDashboardController::class, 'index'])->name('chs.show');
    Route::post('/cshdashboard/assign-college', [CHSDashboardController::class, 'assignCollege'])->name('chs.assign-college');
    Route::post('/cshdashboard/assign-players', [CHSDashboardController::class, 'store'])->name('chs.assign-players');
    Route::resource('studentplayer', StudentPalakasanController::class);
});

// Sub Admin routes
Route::middleware(['web', 'auth', 'subAdminMiddleware'])->group(function () {
    Route::get('/secretary', [SubAdminController::class, 'index'])->name('subadmin.show');
    Route::get('/sa-sportview/{sport}', function ($sport) {
        $sportModel = AssignedSports::findOrFail($sport);
    
        switch ($sportModel->setup) {
            case 'Single Elimination':
                return app(SingleEliminationController::class)->subIndex($sportModel);
            case 'Double Elimination':
                return app(DoubleEliminationController::class)->subIndex($sportModel);
            case 'Free for All':
                return app(FreeForAllController::class)->subIndex($sportModel);
            case 'Round Robin':
                return app(RoundRobinController::class)->subIndex($sportModel);
            default:
                abort(404, 'Invalid sport setup');
        }
    })->name('sa-sportview.index');
});

//

// Sports Landing Page Routes
Route::get('/', [SportsLandingController::class, 'index'])->name('sports.landing');
Route::get('/sports', [SportsLandingController::class, 'sportsIndex'])->name('sports.sports');
Route::get('/sports/schedules', [SportsLandingController::class, 'scheduleIndex'])->name('sports.schedules');
Route::get('/rankings', [SportsLandingController::class, 'rankingIndex'])->name('sports.rankings');

Route::get('/sports/{sportId}/matches', [SportsLandingController::class, 'getSportMatches'])->name('sports.matches');
Route::get('/sports/{sportId}/standings', [SportsLandingController::class, 'getSportStandings'])->name('sports.standings');
Route::get('/teams/{teamId}/matches', [SportsLandingController::class, 'getTeamMatches'])->name('teams.matches');

//sports setup
Route::get('sportview/{sport}', function ($sport) {
    $sportModel = AssignedSports::findOrFail($sport);
    
    switch ($sportModel->setup) {
        case 'Single Elimination':
            return app(SingleEliminationController::class)->homeIndex($sportModel);
        case 'Double Elimination':
            return app(DoubleEliminationController::class)->homeIndex($sportModel);
        case 'Free for All':
            return app(FreeForAllController::class)->homeIndex($sportModel);
        case 'Round Robin':
            return app(RoundRobinController::class)->homeIndex($sportModel);
        default:
            abort(404, 'Invalid sport setup');
    }
})->name('home-sportview.index');