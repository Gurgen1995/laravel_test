<?php

use App\Http\Controllers\LegalEntityAuth\AuthenticatedSessionController;
use App\Http\Controllers\LegalEntityAuth\ConfirmablePasswordController;
use App\Http\Controllers\LegalEntityAuth\EmailVerificationNotificationController;
use App\Http\Controllers\LegalEntityAuth\EmailVerificationPromptController;
use App\Http\Controllers\LegalEntityAuth\NewPasswordController;
use App\Http\Controllers\LegalEntityAuth\PasswordController;
use App\Http\Controllers\LegalEntityAuth\PasswordResetLinkController;
use App\Http\Controllers\LegalEntityAuth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:legalEntity')->group(function () {

    Route::get('legalEntity/login', [AuthenticatedSessionController::class, 'create'])
        ->name('legalEntity.login');

    Route::post('legalEntity/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('legalEntity/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('legalEntity.password.request');

    Route::post('legalEntity/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('legalEntity.password.email');

    Route::get('legalEntity/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('legalEntity.password.reset');

    Route::post('legalEntity/reset-password', [NewPasswordController::class, 'store'])
        ->name('legalEntity.password.store');
});

Route::middleware('auth:legalEntity')->group(function () {
    Route::get('legalEntity/verify-email', EmailVerificationPromptController::class)
        ->name('legalEntity.verification.notice');

    Route::get('legalEntity/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('legalEntity.verification.verify');

    Route::post('legalEntity/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('legalEntity.verification.send');

    Route::get('legalEntity/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('legalEntity.password.confirm');

    Route::post('legalEntity/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('legalEntity/password', [PasswordController::class, 'update'])->name('legalEntity.password.update');

    Route::post('legalEntity/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('legalEntity.logout');
});

