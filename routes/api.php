<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\OccasionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\WeekController;

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

    //Department Routes
    Route::group(['prefix' => 'department'], function(){
        Route::post('create', [DepartmentController::class, 'create'])->name('create.department'); //create a department
        Route::post('update', [DepartmentController::class, 'update'])->name('update.department'); //update a department
        Route::get('delete/{id}', [DepartmentController::class, 'delete'])->name('delete.department'); //delete department
        Route::get('listDepartment', [DepartmentController::class, 'listDepartment'])->name('list.department'); //list all departments
    });

    //Course routes
    Route::group(['prefix' => 'course'], function(){
        Route::post('create', [CourseController::class, 'create'])->name('create.course'); //create a new course
        Route::get('listCourses', [CourseController::class, 'listCourses'])->name('list.courses'); //list all courses
        Route::get('listCourseInRoom/{id}', [CourseController::class, 'listCourseInRoom'])->name('list.courses.department'); //list course in a particular department
        Route::post('update', [CourseController::class, 'update'])->name('update.course'); //update a course
        Route::get('delete/{id}', [CourseController::class, 'delete'])->name('delete.course'); //delete a course
    });

    //Room routes
    Route::group(['prefix' => 'room'], function(){
        Route::post('create', [RoomController::class, 'create'])->name('create.room'); //create a new room
        Route::get('listRooms', [RoomController::class, 'listRooms'])->name('list.room'); //list all rooms
        Route::get('listRoomsInDepartment/{id}', [RoomController::class, 'listRoomsInDepartment'])->name('list.rooms.department'); //list rooms in a particular department
        Route::post('update', [RoomController::class, 'update'])->name('update.room'); //update a room
        Route::get('delete/{id}', [RoomController::class, 'delete'])->name('delete.room'); //delete a room
    });

    //classroom routes
    Route::group(['prefix' => 'classroom'], function(){
        Route::post('create', [ClassRoomController::class, 'create'])->name('create.classroom'); //create a new classroom
        Route::get('listClassRooms', [ClassRoomController::class, 'listClassRooms'])->name('list.classrooms'); //list all classrooms
        Route::get('listClassRoomsInRoom/{id}', [ClassRoomController::class, 'listClassRoomsInRoom'])->name('list.classrooms.department'); //list classroom in a particular department
        Route::post('update', [ClassRoomController::class, 'update'])->name('update.classroom'); //update a classroom
        Route::get('delete/{id}', [ClassRoomController::class, 'delete'])->name('delete.classroom'); //delete a classroom

    });

    //user routes
    Route::group(['prefix' => 'user'], function () {
        Route::post('create', [UserController::class, 'create'])->name('create.user'); //create a new user
        Route::get('listUsers', [UserController::class, 'listUsers'])->name('listUsers.user');
        Route::get('delete/{id}', [UserController::class, 'deleteUser'])->name('deleteUser.user'); //delete a user
    });


    //week Routes
    Route::group(['prefix' => 'week'], function(){
        Route::post('create', [WeekController::class, 'create'])->name('create.week'); //create a week
        Route::post('update', [WeekController::class, 'update'])->name('update.week'); //update a week
        Route::get('delete/{id}', [WeekController::class, 'delete'])->name('delete.week'); //delete week
        Route::get('listWeeks', [WeekController::class, 'listWeeks'])->name('list.weeks'); //list all weeks
        Route::get('listActiveWeek', [WeekController::class, 'listActiveWeek'])->name('list.active.week'); //list active week
        Route::get('changeStatus/{id}', [WeekController::class, 'changeStatus'])->name('change.status.week'); //change status of a week
    });


    //occasion Routes
    Route::group(['prefix' => 'occasion'], function(){
        Route::post('create', [OccasionController::class, 'create'])->name('create.occasion'); //create a occasion
        Route::post('update', [OccasionController::class, 'update'])->name('update.occasion'); //update a occasion
        Route::get('delete/{id}', [OccasionController::class, 'delete'])->name('delete.occasion'); //delete occasion
        Route::get('listOccasions', [OccasionController::class, 'listOccasions'])->name('list.occasions'); //list all occasions
        Route::post('occasionToTimetable', [OccasionController::class, 'occasionToTimetable'])->name('occasion.to.timetable'); //add an occasion to the timeline
        Route::post('removeOccasionToTimetable', [OccasionController::class, 'removeOccasionToTimetable'])->name('remove.occasion.to.timetable'); //remove an occasion to the timeline
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

        Route::get('initialize', [TimetableController::class, 'initialize'])->name('initialize.timetable'); //initial timetable for active week
        Route::get('listTimeTable', [TimetableController::class, 'listTimeTable'])->name('list.timetable'); //list the time table of the active week

        //teachers availability routes
        Route::group(['prefix' => 'teacherAvailability'], function(){
            
        });
    });
});
