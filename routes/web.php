<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\IndexController::class, 'index'])->name('index');
Route::get('/feedback/detail/{id}', [App\Http\Controllers\IndexController::class, 'getFeedback']);


Route::group(['middleware' => ['auth','check.admin']], function () {
        Route::get('/dashboard', function () {
            return view('dashboard/dashboard');
        })->name('dashboard');

        Route::get('/admin-profile', [App\Http\Controllers\UserController::class, 'getAdminProfile'])->name('admin-profile');
        Route::post('/update-admin-profile', [App\Http\Controllers\UserController::class, 'UpdateAdminProfile'])->name('update-admin-profile');   
        Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');   
        Route::post('/edit-user/{user_id}', [App\Http\Controllers\UserController::class, 'edit'])->name('edit-user');
        Route::post('/update-user', [App\Http\Controllers\UserController::class, 'update'])->name('update-user');
        Route::delete('/delete-user/{user_id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('delete-user');


        Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'getFeedback'])->name('feedback');   
        Route::get('/edit-feedback/{feedback_id}', [App\Http\Controllers\FeedbackController::class, 'edit'])->name('edit-feedback');
        Route::post('/post-update-feedback', [App\Http\Controllers\FeedbackController::class, 'update'])->name('post-update-feedback');
        Route::delete('/delete-feedback/{user_id}', [App\Http\Controllers\FeedbackController::class, 'destroy'])->name('delete-feedback');


});
Route::group(['middleware' => 'auth'], function () {
    Route::post('/post-add-feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('post-add-feedback');
    Route::get('/add-feedback', [App\Http\Controllers\FeedbackController::class, 'index'])->name('add-feedback');
    Route::post('/post-vote', [App\Http\Controllers\VoteController::class, 'store'])->name('post-vote');
    Route::post('/save-comment', [App\Http\Controllers\CommentController::class, 'store'])->name('save-comment');

});

require __DIR__.'/auth.php';
