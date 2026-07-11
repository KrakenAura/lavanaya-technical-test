<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SubmissionController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ApprovalController;
use App\Http\Controllers\Api\V1\AttachmentController;


Route::prefix('v1')->group(function () {

    Route::post(
        'login',
        [AuthController::class, 'login']
    );


    Route::middleware('auth:sanctum')
        ->group(function () {

            Route::post(
                'logout',
                [AuthController::class, 'logout']
            );

            Route::apiResource(
                'submissions',
                SubmissionController::class
            );

            Route::post(
                'submissions/{submission}/submit',
                [SubmissionController::class, 'submit']
            );

            Route::post(
                'approvals/{approval}/approve',
                [ApprovalController::class, 'approve']
            );

            Route::post(
                'approvals/{approval}/reject',
                [ApprovalController::class, 'reject']
            );
            Route::post(
                'submissions/{submission}/attachments',
                [AttachmentController::class, 'store']
            );
            Route::delete(
                'attachments/{attachment}',
                [AttachmentController::class, 'destroy']
            );
        });
});
