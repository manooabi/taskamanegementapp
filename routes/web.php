<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', function () {
    return view('admin.dashboard'); // Load the dashboard view
});

Route::get('/tasks', function () {
    return view('admin.tasks'); // Load the tasks management view
});

Route::get('/users', function () {
    return view('admin.users'); // Load the tasks management view
});