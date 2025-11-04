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


Route::get('/test-fcm', function (\App\Http\Services\Feature\Notification\FcmService $fcm) {
    $token = 'fBuyuuz6Rv21cLN_UN9uk3:APA91bFTWwGmqx_RfkHsKQkOFweF1PJt9kIgZ24MVDFdCKSA71JCqTa88iBjnLuR1zvoC0kk4ChrIF6_q5eimKWXbiHacqahmONxi0hdKcZ_uRo3SJRUO6Y';

    $ok = $fcm->sendToToken(
        $token,
        'Laravel → Firebase',
        'This is a test message',
        ['click_action' => 'FLUTTER_NOTIFICATION_CLICK']
    );

    dd($ok);

    return $ok ? 'Sent ✅' : 'Failed ❌';
});
