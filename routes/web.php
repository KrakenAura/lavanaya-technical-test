<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\SubmissionController;
use App\Http\Controllers\Web\ApprovalController;
use App\Http\Controllers\Web\PaymentController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('web.dashboard')
        : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])
    ->name('web.')
    ->group(function () {

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');


        Route::resource(
            'submissions',
            SubmissionController::class
        );
        Route::post(
            '/submissions/{submission}/submit',
            [SubmissionController::class, 'submit']
        )->name('submissions.submit');


        Route::get(
            '/approvals',
            [ApprovalController::class, 'index']
        )->name('approvals.index');

        Route::get(
            '/approvals/{approval}',
            [ApprovalController::class, 'show']
        )->name('approvals.show');

        Route::post(
            '/approvals/{approval}/approve',
            [ApprovalController::class, 'approve']
        )->name('approvals.approve');

        Route::post(
            '/approvals/{approval}/reject',
            [ApprovalController::class, 'reject']
        )->name('approvals.reject');


        Route::get(
            '/payments',
            [PaymentController::class, 'index']
        )->name('payments.index');

        Route::get(
            '/payments/{payment}',
            [PaymentController::class, 'show']
        )->name('payments.show');

        Route::post(
            '/payments/{payment}/process',
            [PaymentController::class, 'process']
        )->name('payments.process');

        Route::post(
            '/payments/{payment}/reject',
            [PaymentController::class, 'reject']
        )->name('payments.reject');


        Route::get(
            '/profile',
            [ProfileController::class, 'edit']
        )->name('profile.edit');

        Route::patch(
            '/profile',
            [ProfileController::class, 'update']
        )->name('profile.update');

        Route::delete(
            '/profile',
            [ProfileController::class, 'destroy']
        )->name('profile.destroy');
    });

require __DIR__ . '/auth.php';
