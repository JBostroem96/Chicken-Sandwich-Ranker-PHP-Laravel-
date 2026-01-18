<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageViewController;
use App\Http\Controllers\ChickenSandwichController;
use App\Http\Controllers\UserChickenSandwichController;
use App\Models\User;

// Home page
Route::get('/', [PageViewController::class, 'home'])->name('home');

// Group all routes handled by PageViewController
Route::controller(PageViewController::class)->group(function () {
    
    // Public Routes
    Route::get('/search', 'search')->name('search');
    Route::get('/sign-up', 'signup')->name('signup');
    Route::get('/login', 'login')->name('login');
    Route::get('/password-reset', 'password')->name('password.reset');

    // middleware/authenticated route, ensures only signed in users can access
    Route::middleware(['auth'])->group(function () {
        Route::get('/submit', 'submit')->name('submit');
        Route::post('/submit', [UserChickenSandwichController::class, 'store'])->name('store');
        Route::get('/profile/change-password', [PageViewController::class, 'changePassword'])->name('profile.change-password');
        Route::post('/profile/change-password', [User::class, 'changePassword'])->name('profile.password.update');
        Route::prefix('profile')->group(function () {

            Route::get('/', [PageViewController::class, 'profile'])->name('profile');
            Route::resource('ratings', UserChickenSandwichController::class)
                ->names([
                    'index' => 'profile.ratings.index',
                    'edit' => 'profile.ratings.edit',
                    'update' => 'profile.ratings.update',
                    'destroy' => 'profile.ratings.destroy',
                ]);
                
        });
        
    });
});

Route::resource('user-chicken-sandwiches', UserChickenSandwichController::class);
Route::resource('chicken-sandwiches', ChickenSandwichController::class);


Route::resource('user', UserController::class);

Route::get('/results', [ChickenSandwichController::class, 'displayResults'])->name('results');

// Optional: Dashboard (only if needed)
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Laravel Breeze profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze auth routes (register, login, password, etc.)
require __DIR__.'/auth.php';
