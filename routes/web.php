<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;

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

/**
 * Common Resource Routes:
 * index - show all listings
 * show - show single listing
 * create - show form to create new listing
 * store - save new listing to database
 * edit - show form to edit listing
 * update - save updated listing to database
 * destroy - delete listing from database
 */

Route::get('/', [ListingController::class, 'index']);
Route::get('/listings/create', [ListingController::class, 'create']);
Route::post('/listings', [ListingController::class, 'store']);
Route::get('/listings/{listing}', [ListingController::class, 'show']);