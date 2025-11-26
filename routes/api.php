<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ApiController;

Route::get('/apotek', [ApiController::class, 'TampilApotek']);
Route::get('/apotek/show/{id}', [ApiController::class, 'ShowApotek']);