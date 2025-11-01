<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Services\Systems\Tool\Autoloader;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Kreait\Firebase\Factory;


Autoloader::loadFilesRecursivelyInDirs([__DIR__ . '/web/']);



Route::get('admin/dashboard', [DashboardController::class, 'Home'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

//Route::get('/test-fcm', function (\App\Http\Services\Feature\User\SendNotificationService $fcm) {
//    $token = 'eLurOanbQA-04IvJ1f70BJ:APA91bHgsatgYEopEQQIQIvaZUgB-HwrrlAgGVSkN3jquHuUoddLhvQc1idlCT3BQj-636_Fe8FP5WTlMbdxAbY7vEth-hOpA5TB52tERGVQRL_zzdpzThc';
//
//    $ok = $fcm->sendToToken(
//        $token,
//        'Laravel → Firebase',
//        'This is a test message',
//        ['click_action' => 'FLUTTER_NOTIFICATION_CLICK']
//    );
//
//    return $ok ? 'Sent ✅' : 'Failed ❌';
//});


Route::get('/test-fcm', function (\App\Http\Services\Feature\User\RawNotification $fcm) {
    $token = 'eLurOanbQA-04IvJ1f70BJ:APA91bHgsatgYEopEQQIQIvaZUgB-HwrrlAgGVSkN3jquHuUoddLhvQc1idlCT3BQj-636_Fe8FP5WTlMbdxAbY7vEth-hOpA5TB52tERGVQRL_zzdpzThc';

    $ok = $fcm->sendToToken(
        $token,
        'Laravel → Firebase',
        'This is a test message',
        ['click_action' => 'FLUTTER_NOTIFICATION_CLICK']
    );

    dd($ok);

    return $ok ? 'Sent ✅' : 'Failed ❌';
});
