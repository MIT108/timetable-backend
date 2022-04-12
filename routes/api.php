<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

//test routes
Route::get('/test', [AuthController::class, 'test'])->name('test');


//user route
Route::post('login', [AuthController::class, 'login'])->name('login.user');
Route::post('register', [AuthController::class, 'register'])->name('register.user');
//verification email address
Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

    //authentication routes
    Route::post('logout', [AuthController::class, 'logout'])->name('logout.user');
    Route::post('changePassword', [AuthController::class, 'changePassword'])->name('changePassword.user');

    //role routes
    Route::group(['prefix' => 'role'], function(){
        Route::post('create', [RoleController::class, 'create'])->name('create.role'); //create a new user role
        Route::post('update', [RoleController::class, 'update'])->name('update.role'); //update a user role
        Route::get('listAll', [RoleController::class, 'listAll'])->name('listAll.role'); //list all users roles
        Route::get('delete/{id}', [RoleController::class, 'delete'])->name('delete.role'); //delete user role
    });

    //user routes
    Route::group(['prefix' => 'user'], function () {
        Route::post('create', [UserController::class, 'create'])->name('create.user'); //create a new user
        Route::get('listUsers', [UserController::class, 'listUsers'])->name('listUsers.user');
        Route::get('delete/{id}', [UserController::class, 'deleteUser'])->name('deleteUser.user'); //delete a user
    });
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
