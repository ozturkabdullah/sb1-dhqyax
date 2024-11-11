<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{category}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/blog/category/{category}', [PostController::class, 'category'])->name('posts.category');
Route::get('/blog/tag/{tag}', [PostController::class, 'tag'])->name('posts.tag');

// Contact routes
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Auth required routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Rental routes
    Route::get('/rentals/create/{category}', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals/{category}', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}/invoice', [RentalController::class, 'invoice'])->name('rentals.invoice');
    Route::post('/rentals/{rental}/invoice', [RentalController::class, 'storeInvoice'])->name('rentals.store-invoice');
    Route::get('/rentals/{rental}/payment', [RentalController::class, 'payment'])->name('rentals.payment');
    Route::post('/rentals/{rental}/payment', [RentalController::class, 'processPayment'])->name('rentals.process-payment');
    Route::get('/rentals/{rental}/bank-transfer', [RentalController::class, 'bankTransferInfo'])->name('rentals.bank-transfer-info');
    Route::get('/rentals/my-rentals', [RentalController::class, 'myRentals'])->name('rentals.my-rentals');
});