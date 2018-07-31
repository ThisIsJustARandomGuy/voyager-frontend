<?php

use Pvtl\VoyagerFrontend\Page;
use Illuminate\Support\Facades\Request;

$accountController = config('voyager-frontend.controllers.account', '\Pvtl\VoyagerFrontend\Http\Controllers\AccountController');
$searchController = config('voyager-frontend.controllers.search',  '\Pvtl\VoyagerFrontend\Http\Controllers\SearchController');

/**
 * Authentication
 */
if(config('voyager-frontend.enable_auth', true)) {
    Route::group(['middleware' => ['web']], function () use ($accountController) {
        Route::group(['namespace' => config('voyager-frontend.controllers.auth_namespace', 'App\Http\Controllers')], function () {
            Auth::routes();
        });
    
        Route::group(['middleware' => 'auth', 'as' => 'voyager-frontend.account'], function () use ($accountController) {
            Route::get('/account', "$accountController@index");
            Route::post('/account', "$accountController@updateAccount");
    
            /**
             * User impersonation
             */
            Route::get('/admin/users/impersonate/{userId}', "$accountController@impersonateUser")
                ->name('.impersonate')
                ->middleware(['web', 'admin.user']);
    
            Route::post('/admin/users/impersonate/{originalId}', "$accountController@impersonateUser")
                ->name('.impersonate')
                ->middleware(['web']);
        });
    });
}

Route::group([
    'as' => 'voyager-frontend.pages.',
    'prefix' => 'admin/pages/',
    'middleware' => ['web', 'admin.user'],
    'namespace' => config('voyager-frontend.controllers.namespace', '\Pvtl\VoyagerFrontend\Http\Controllers')
], function () {
    Route::post('layout/{id?}', ['uses' => "PageController@changeLayout", 'as' => 'layout']);
});

/**
 * Let's get some search going
 */
if(config('voyager-frontend.enable_search', true)) {
    Route::get('/search', "$searchController@index")
        ->middleware(['web'])
        ->name('voyager-frontend.search');
}
