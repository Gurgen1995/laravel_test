<?php

use App\Http\Controllers\IndividualAuth\AuthenticatedSessionController;
use App\Http\Controllers\IndividualAuth\ConfirmablePasswordController;
use App\Http\Controllers\IndividualAuth\EmailVerificationNotificationController;
use App\Http\Controllers\IndividualAuth\EmailVerificationPromptController;
use App\Http\Controllers\IndividualAuth\NewPasswordController;
use App\Http\Controllers\IndividualAuth\PasswordController;
use App\Http\Controllers\IndividualAuth\PasswordResetLinkController;
use App\Http\Controllers\IndividualAuth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:individual')->group(function () {

    Route::get('individual/login', [AuthenticatedSessionController::class, 'create'])
        ->name('individual.login');

    Route::post('individual/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('individual/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('individual.password.request');

    Route::post('individual/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('individual.password.email');

    Route::get('individual/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('individual.password.reset');

    Route::post('individual/reset-password', [NewPasswordController::class, 'store'])
        ->name('individual.password.store');
});

Route::middleware('auth:individual')->group(function () {
    Route::get('individual/verify-email', EmailVerificationPromptController::class)
        ->name('individual.verification.notice');

    Route::get('individual/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('individual.verification.verify');

    Route::post('individual/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('individual.verification.send');

    Route::get('individual/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('individual.password.confirm');

    Route::post('individual/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('individual/password', [PasswordController::class, 'update'])->name('individual.password.update');

    Route::post('individual/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('individual.logout');
});
