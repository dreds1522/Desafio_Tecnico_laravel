<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;



Route::get('/', [NewsController::class, 'index'])->name('search.index');
Route::get('/buscas', [NewsController::class, 'searches'])->name('searches.index');
