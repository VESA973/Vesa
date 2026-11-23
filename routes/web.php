<?php

use App\Http\Controllers\ContactRequestController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RepairController;
use App\Models\RepairDevice;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/solutions', 'pages.solutions')->name('solutions');
Route::get('/reparation', [RepairController::class, 'index'])->name('reparation');
Route::get('/reparation/{family}', [RepairController::class, 'show'])
    ->whereIn('family', array_keys(RepairDevice::FAMILIES))
    ->name('reparation.family');
Route::get('/realisations', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/realisations/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/mentions-legales', 'pages.legal')->name('legal');
Route::view('/cookies', 'pages.cookies')->name('cookies');
Route::post('/contact', [ContactRequestController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
