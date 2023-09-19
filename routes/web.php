<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserContoller;
use App\Models\User;
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
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


          //////////////// Admin Route ///////////////////

Route::middleware('auth', 'roles:admin')->group (function(){

    Route::get('/admin/dashboard', [AdminController::class, 'admindashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'adminlogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'adminprofile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'adminprofilestore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'adminchangepassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'adminupdatepassword'])->name('admin.update.password');
    Route::get('/admin/user/list', [AdminController::class, 'adminuserlist'])->name('admin.user.list');
    Route::get('/user/status/{id}', [AdminController::class, 'userstatus'])->name('user.status');
    Route::get('/user/password/reset/{id}', [AdminController::class, 'userpasswordreset'])->name('user.password.reset');
    Route::post('/user/password/update/{id}', [AdminController::class, 'userpasswordupdate'])->name('user.password.update');
    Route::get('/user/profile/edit/{id}', [AdminController::class, 'userprofileedit'])->name('user.profile.edit');
    Route::post('/user/profile/update/{id}', [AdminController::class, 'userprofileupdate'])->name('user.profile.update');
    Route::get('/user/profile/delete/{id}', [AdminController::class, 'userprofiledelete'])->name('user.profile.delete');

});   /// End of Admin Route 


          //////////////// User Route ///////////////////

Route::middleware('auth', 'roles:user')->group (function(){

    Route::get('/user/dashboard', [UserContoller::class, 'userdashboard'])->name('user.dashboard');
    Route::get('/user/logout', [UserContoller::class, 'userlogout'])->name('user.logout');
    Route::get('/user/profile', [UserContoller::class, 'userprofile'])->name('user.profile');
    Route::post('/user/profile/store', [UserContoller::class, 'userprofilestore'])->name('user.profile.store');
    Route::get('/user/change/password', [UserContoller::class, 'userchangepassword'])->name('user.change.password');
    Route::post('/user/update/password', [UserContoller::class, 'userupdatepassword'])->name('user.update.password');

});   /// End of User Route 
