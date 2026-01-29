<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageViewController;
use App\Http\Controllers\ChickenSandwichController;
use App\Http\Controllers\UserChickenSandwichController;
use App\Models\User;


// Home page
Route::get('/', [PageViewController::class, 'home'])->name('home');

Route::get('/submit', [PageViewController::class, 'submit'])
    ->middleware(['auth', 'role:admin'])
    ->name('submit');

Route::resource('chicken-sandwiches', ChickenSandwichController::class);


// Group all routes handled by PageViewController
Route::controller(PageViewController::class)->group(function () {
    
    // Public Routes
    Route::get('/search', 'search')->name('search');
    Route::get('/sign-up', 'signup')->name('signup');
    Route::get('/login', 'login')->name('login');
    Route::get('/password-reset', 'password')->name('password.reset');

    Route::middleware(['auth', 'role:admin'])->group(function () {

        Route::post('/submit', [ChickenSandwichController::class, 'store'])->name('store');
        Route::get('/chicken-sandwiches/{id}/edit', [ChickenSandwichController::class, 'edit']);
        Route::post('/chicken-sandwiches/{id}/edit', [ChickenSandwichController::class, 'edit']);
        Route::delete('/delete/{id}', [ChickenSandwichController::class, 'destroy']);
    });
    // middleware/authenticated route, ensures only signed in users can access
    Route::middleware(['auth'])->group(function () {
       
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


Route::resource('user', UserController::class);

Route::get('/results', [ChickenSandwichController::class, 'displayResults'])->name('results');

// Breeze auth routes (register, login, password, etc.)
require __DIR__.'/auth.php';
