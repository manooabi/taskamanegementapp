<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Web\StripeController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('admin.dashboard'); // Load the dashboard view
});

Route::get('/tasks', function () {
    return view('admin.tasks'); // Load the tasks management view
});

Route::get('/users', function () {
    return view('admin.users'); // Load the tasks management view
});

Route::get('/static-toggle', function () {
    return view('admin.toggle');
});



Route::get('/add-task', function () {
    return view('admin.createTask');
});

// Route::get('/edit-task/{id}', function ($id) {
//     return view('admin.UpdateTask', ['taskId' => $id]);
// })->name('edit-task');


Route::get('/edit-task/{id}', function ($id) {
    $task = \App\Models\Task::find($id); // Fetch task from the database

    if (!$task) {
        abort(404, 'Task not found');
    }

    return view('admin.UpdateTask', ['task' => $task]);
});

// Route::post('/checkout', StripeController::class,'checkout');
// Route::get('/success', StripeController::class,'success');

//Route::get('/checkout', StripeController::class,'checkout')->name('checkout');
Route::get('/checkout', [StripeController::class, 'checkout'])->name('checkout');
Route::get('/success', [StripeController::class, 'success'])->name('success');

Route::get('/admin/tasks', [StripeController::class, 'index'])->name('admin.tasks');
//Route::get('/success', StripeController::class,'success');

// Route::get('/checkout', function() {
//     return view('checkout'); // You may want to create a checkout view if needed
// });