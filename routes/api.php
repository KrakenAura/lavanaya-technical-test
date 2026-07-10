<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SubmissionController;
use App\Http\Controllers\Api\V1\AuthController;


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
        });
});
