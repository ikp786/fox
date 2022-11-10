<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PatientsController;
use App\Http\Controllers\Admin\SliderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::fallback(function () {
    return response()->json([
        'ResponseCode'  => 404,
        'status'        => False,
        'message'       => 'URL not found as you looking'
    ]);
});

Route::get('/', function () {
    $title   = 'dashboard';
    $data    = compact('title');
    return view('admin.login', $data);
});

Route::group(['prefix' => 'admin'], function () {
    Route::post('login', [AdminController::class, 'login'])->name('admin.login');
    Route::get('/', function () {
        return view('admin.login');
    })->name('admin');
    Route::group(['middleware'  => 'admin'], function () {

    Route::controller(DashboardController::class)->group(function () {
        Route::get('dashboard', 'index')->name('admin.dashboard');
    });

    Route::group(['prefix' => 'doctors'], function () {
        Route::controller(DoctorController::class)->group(function () {
            Route::get('index', 'index')->name('admin.doctors.index');
            Route::get('mentor_request', 'mentorRequest')->name('admin.doctors.mentor_request');
        });
    });

    Route::group(['prefix' => 'patients'], function () {
        Route::controller(PatientsController::class)->group(function () {
            Route::get('index', 'index')->name('admin.patients.index');
        });
    });

});


/*
|--------------------------------------------------------------------------
| FAQS CREATE STORE DELETE UPDATE EDIT 
|--------------------------------------------------------------------------
*/
Route::controller(FaqController::class)->group(function () {
    Route::group(['prefix' => 'faqs'], function () {
        Route::get('index', 'index')->name('admin.faqs.index');
        Route::get('create', 'create')->name('admin.faqs.create');
        Route::post('store', 'store')->name('admin.faqs.store');
        Route::get('edit/{id}', 'edit')->name('admin.faqs.edit');
        Route::PATCH('update/{id}', 'update')->name('admin.faqs.update');
        Route::delete('destroy{id}', 'destroy')->name('admin.faqs.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| SLIDER CREATE STORE DELETE UPDATE EDIT
|--------------------------------------------------------------------------
*/
        Route::controller(SliderController::class)->group(function () {
            Route::group(['prefix' => 'sliders'], function () {
                Route::get('index', 'index')->name('admin.sliders.index');
                Route::get('create', 'create')->name('admin.sliders.create');
                Route::post('store', 'store')->name('admin.sliders.store');
                Route::get('edit/{id}', 'edit')->name('admin.sliders.edit');
                Route::PATCH('update/{id}', 'update')->name('admin.sliders.update');
                Route::delete('destroy{id}', 'destroy')->name('admin.sliders.destroy');
            });
        });



});
