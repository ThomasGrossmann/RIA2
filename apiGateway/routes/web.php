<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/analyze', function (Request $request) {
    Http::get('http://localhost:5000/analyze'); // TODO : Change the port to whatever will be the port of the labelDetector
    return response()->json(['message' => 'Analyze request sent to localhost:5000']);
});
