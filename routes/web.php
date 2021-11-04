<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Landlord\RegisterTenantController;
use App\Http\Controllers\Landlord\AuthController;

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

Route::domain('{tenant}.localhost')->middleware('tenant')->group(function(){
    Route::view('/', 'welcome');
});

Route::middleware('landlord')->group(function () {
    Route::view('/', 'welcome');
    Route::get('signup',[AuthController::class, 'register'])->name('user.create');
    Route::post('post/signup',[AuthController::class, 'postRegistration'])->name('user.store');
    Route::get('signin',[AuthController::class, 'index'])->name('user.login');
    Route::post('post/signin',[AuthController::class, 'signin'])->name('signin.post');
    Route::get('signout', [AuthController::class, 'signOut'])->name('user.signout');

    Route::middleware('landlord-auth')->group(function () {
        Route::get('client-dashboard',[AuthController::class, 'dashboard'])->name('client.dashboard');
        Route::post('post/company',[RegisterTenantController::class, 'store'])->name('tenant.store');
        Route::get('edit/tenant',[RegisterTenantController::class, 'edit'])->name('tenant.edit');
        Route::put('update/tenant/{id}',[RegisterTenantController::class, 'update'])->name('tenant.update');
        Route::delete('delete/tenant/{id}',[RegisterTenantController::class, 'deleteTenant'])->name('tenant.delete');
    });
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
