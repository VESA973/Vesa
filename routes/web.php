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

// Addresses of the previous v-esa.fr site, permanently redirected so old links and search results keep working.
Route::permanentRedirect('/reparation-consoles', '/reparation/consoles');
Route::permanentRedirect('/reparation-instruments', '/reparation/instruments');
Route::permanentRedirect('/telesecretariat', '/solutions');
Route::permanentRedirect('/rendez-vous', '/contact');
Route::permanentRedirect('/cgv', '/mentions-legales');
Route::permanentRedirect('/confidentialite', '/mentions-legales');
Route::permanentRedirect('/retours', '/mentions-legales');
Route::permanentRedirect('/services', '/');
Route::permanentRedirect('/a-propos', '/');
Route::permanentRedirect('/assistance-administrative', '/');
Route::permanentRedirect('/boutique', '/');
