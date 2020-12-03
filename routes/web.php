<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DespesaController;



Route::get('/', [DashboardController::class, 'auth'])->name('root');


//==========User==========
Route::get('/login', function () { return view('user.login'); })->name('user.login.index');
Route::post('/login', [UserController::class, 'login'])->name('user.login');
Route::get('/register', function () { return view('user.register'); })->name('user.register.index');
Route::post('/register', [UserController::class, 'register'])->name('user.register');
Route::get('/logout', [UserController::class, 'logout'])->name('user.logout');


//==========Dashboard==========
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('auth');
Route::post('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index.post')->middleware('auth');


//==========Despesas==========
Route::get('/despesa/criar', [DespesaController::class, 'createView'])->name('despesa.create.index')->middleware('auth');
Route::post('/despesa/criar', [DespesaController::class, 'create'])->name('despesa.create')->middleware('auth');
Route::get('/despesa/{id}/imagem', [DespesaController::class, 'imageView'])->name('despesa.image')->middleware('auth');
Route::get('/despesa/{id}/imagem/get', [DespesaController::class, 'image'])->name('despesa.image.get')->middleware('auth');
Route::get('/despesa/{id}/editar', [DespesaController::class, 'editView'])->name('despesa.edit.index')->middleware('auth');
Route::put('/despesa/{id}', [DespesaController::class, 'edit'])->name('despesa.edit')->middleware('auth');
Route::delete('/despesa/{id}', [DespesaController::class, 'delete'])->name('despesa.delete')->middleware('auth');