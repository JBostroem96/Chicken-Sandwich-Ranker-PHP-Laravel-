<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PageViewController;
use App\Http\Controllers\ChickenSandwichController;
use App\Http\Controllers\UserChickenSandwichController;
use App\Models\User;


// Home page
Route::get('/', [PageViewController::class, 'home'])->name('home');

//Applies to both GET and POST route
Route::get('/submit', [PageViewController::class, 'submit'])
    ->middleware(['auth', 'role:admin'])
    ->name('submit');

Route::post('/submit', [ChickenSandwichController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('chicken-sandwiches.store');

Route::put('/chicken-sandwiches/{id}/edit', [ChickenSandwichController::class, 'update'])
    ->middleware(['auth', 'role:admin'])->name('chicken-sandwiches.update');

Route::get('/chicken-sandwiches/{id}/edit', [ChickenSandwichController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('chicken-sandwiches.update');


Route::delete('/chicken-sandwiches/{id}', [ChickenSandwichController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])->name('chicken-sandwiches.destroy');

Route::get('/chicken-sandwiches', [ChickenSandwichController::class, 'index'])->name('chicken-sandwiches.index');

// Group all routes handled by PageViewController
Route::controller(PageViewController::class)->group(function () {
    
    // Public Routes
    Route::get('/search', 'search')->name('search');
    Route::get('/sign-up', 'signup')->name('signup');
    Route::get('/login', 'login')->name('login');
});

// middleware/authenticated route, ensures only signed in users can access
Route::middleware(['auth'])->group(function () {
       
    Route::get('/profile/change-password', [PageViewController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/chicken-sandwiches', [UserChickenSandwichController::class, 'store'])
        ->name('user-chicken-sandwiches.store');

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

// Breeze auth routes (register, login, password, etc.)
require __DIR__.'/auth.php';