<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PharIo\Manifest\Email;

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

//test routes
Route::get('/test', [AuthController::class, 'test'])->name('test');


//user route
Route::post('login', [AuthController::class, 'login'])->name('login.user');
Route::post('register', [AuthController::class, 'register'])->name('register.user');
//verification email address
Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/products', function () {
        return User::all();
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout.user');

    //user routes
    Route::get('listUsers', [UserController::class, 'listUsers'])->name('listUsers.user');


    //timetable routes
    Route::group(['prefix' => 'timetable'], function(){

        //days route
        Route::group(['prefix' => 'days'], function(){
            Route::post('add', [DayController::class, 'addDay'])->name('addDay.timetable'); //add a new day
            Route::post('update', [DayController::class, 'updateDay'])->name('updateDay.timetable'); //update a  day
            Route::get('list', [DayController::class, 'listDays'])->name('listDay.timetable'); //list all days
            Route::get('list/{status}', [DayController::class, 'listDaysPerStatus'])->name('listDay.timetable'); //list all days per status
            Route::get('delete/{id}', [DayController::class, 'deleteDay'])->name('deleteDay.timetable'); //delete a day
            Route::get('changeStatus/{id}', [DayController::class, 'changeStatus'])->name('changeStatus.timetable'); //change status of a day
        });

        //period routes
        Route::group(['prefix' => 'periods'], function () {
            Route::post('adds', [PeriodController::class, 'addPeriod'])->name('addPeriod.timetable'); //add a period
            Route::get('list', [PeriodController::class, 'listPeriods'])->name('listPeriods.timetable'); //list all periods
            Route::get('list/{day_id}', [PeriodController::class, 'listPeriodsForDay'])->name('listPeriods.timetable'); //list all periods for a day
            Route::post('update', [PeriodController::class, 'updatePeriod'])->name('updatePeriod.timetable'); //update a period
            Route::get("delete/{id}", [PeriodController::class, 'deletePeriod'])->name('deletePeriod.timetable'); //delete a period
        });
    });
});
