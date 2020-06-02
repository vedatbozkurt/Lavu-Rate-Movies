<?php

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


Route::prefix('v1')->group(function () {
    Route::post('register', 'API\AuthController@register');
    Route::post('login', 'API\AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    //
    Route::get('ratemovies', 'API\MovieController@getMovies');
    Route::post('ratemovies', 'API\MovieController@rateMovie');


    Route::middleware('auth:api')->group( function () {
        Route::get('user', 'API\AuthController@user');
        Route::post('user', 'API\UserController@updateProfile');

        Route::post('logout', 'API\AuthController@logout');

        Route::resource('users', 'API\UserController');
        Route::resource('movies', 'API\MovieController');


        //backup
        Route::get('getbackups', 'API\BackupController@getBackups');
        Route::post('downloadbackup', 'API\BackupController@downloadBackup');
        Route::post('createbackup', 'API\BackupController@createBackup');
        Route::post('deletebackup', 'API\BackupController@deleteBackup');
        Route::get('logs', 'API\LogController@index');
        Route::delete('deletelog/{id}', 'API\LogController@deleteLog');
        Route::delete('deletealllogs', 'API\LogController@deleteAllLogs');

        Route::resource('roles', 'API\RoleController');
        Route::resource('permissions', 'API\PermissionController');

        Route::get('/cacheclear', function() { Artisan::call('cache:clear'); });
        Route::get('/cacheview', function() { Artisan::call('view:clear'); });
        Route::get('/cacheroute', function() { Artisan::call('route:clear'); });
        Route::get('/cacheconfig', function() { Artisan::call('config:cache'); });
        Route::get('/cacheclearall', function() { Cache::flush(); });
        Route::get('/dashboard', function() {
            $role = Spatie\Permission\Models\Role::all()->count();
            $permission = Spatie\Permission\Models\Permission::all()->count();
            $activity = Spatie\Activitylog\Models\Activity::all()->count();
            $user = App\User::all()->count();
            return response()->json(array('role'=>$role,'permission'=>$permission,'activity'=>$activity,'user'=>$user));
         });
    });
});
