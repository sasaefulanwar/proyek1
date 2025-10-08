<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReverseController;
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    $title = "MEDIFINDER";
    $slug = "home";
    $konten = "Ini konten";
    return view('home', compact('title', 'slug', 'konten'));
})->name('home');

Route::get('/reverse', [ReverseController::class, 'reverse'])->name('reverse');
