<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::fallback(function () {
    return response()->json([
        'ResponseCode'  => 404,
        'status'        => False,
        'message'       => 'URL not found as you looking'
    ]);
});


/*
        |--------------------------------------------------------------------------
        | AUTHORISATION FAILED ROUTE
        |--------------------------------------------------------------------------
        */

Route::get('login', [AuthController::class, 'unauthorized_access'])->name('login');
/*
|--------------------------------------------------------------------------
| AUTH REGISTER LOGIN SENT LOGIN OTP ROUTE
|--------------------------------------------------------------------------
*/
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    // Route::post('sent_register_otp', 'sentRegisterOtp');
    Route::post('register_otp_verify', 'registerOtpVerify');
    Route::post('register_resent_otp_verify', 'registerReSentOtp');
});

Route::middleware('auth:api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('update_password', 'updatePassword');
        Route::post('update_user_other_detail', 'updateUserOtherDetail');
        Route::post('update_mentor_other_detail', 'updateMentorOtherDetail');
    });
});
