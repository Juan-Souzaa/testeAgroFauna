<?php

use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('livros.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('permission:books.view')->group(function () {
        Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
    });

    Route::middleware('permission:books.create')->group(function () {
        Route::get('/livros/criar', [LivroController::class, 'create'])->name('livros.create');
        Route::post('/livros', [LivroController::class, 'store'])->name('livros.store');
    });

    Route::middleware('permission:books.update')->group(function () {
        Route::get('/livros/{livro}/editar', [LivroController::class, 'edit'])->name('livros.edit');
        Route::put('/livros/{livro}', [LivroController::class, 'update'])->name('livros.update');
    });

    Route::middleware('permission:books.delete')->group(function () {
        Route::delete('/livros/{livro}', [LivroController::class, 'destroy'])->name('livros.destroy');
    });

    Route::middleware('permission:users.manage')->group(function () {
        Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/usuarios', [UserController::class, 'store'])->name('admin.users.store');
        Route::patch('/admin/usuarios/{user}/papel', [UserController::class, 'updateRole'])->name('admin.users.role');
        Route::delete('/admin/usuarios/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    Route::middleware('permission:logs.view')->group(function () {
        Route::get('/admin/logs', [LogController::class, 'index'])->name('admin.logs.index');
    });
});

require __DIR__.'/auth.php';
