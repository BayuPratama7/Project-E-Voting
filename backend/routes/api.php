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

// API Version 1
Route::prefix('v1')->namespace('Api\V1')->group(function () {
    
    // Public routes (no authentication required)
    Route::post('auth/admin/login', 'AuthController@adminLogin');
    Route::post('auth/pemilih/login', 'AuthController@pemilihLogin');
    
    // Public voting results (can be accessed by anyone)
    Route::get('voting/results', 'VotingController@results');
    Route::get('voting/status', 'VotingController@status');
    Route::get('voting/statistics', 'VotingController@statistics');
    
    // Public dashboard statistics
    Route::get('dashboard/statistics', 'DashboardController@statistics');

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Authentication routes
        Route::post('auth/logout', 'AuthController@logout');
        Route::get('auth/me', 'AuthController@me');
        Route::get('auth/check', 'AuthController@check');

        // Admin only routes
        Route::middleware('admin')->group(function () {
            // Admin dashboard
            Route::get('dashboard/admin', 'DashboardController@admin');
            
            // Pemilih management
            Route::apiResource('pemilih', 'PemilihController');
            Route::get('pemilih/search', 'PemilihController@search');
            Route::get('pemilih/statistics', 'PemilihController@statistics');
            
            // Kandidat management
            Route::apiResource('kandidat', 'KandidatController');
            Route::get('kandidat/statistics', 'KandidatController@statistics');
            Route::get('kandidat/results', 'KandidatController@results');
        });

        // Pemilih routes
        Route::middleware('pemilih')->group(function () {
            // Pemilih dashboard
            Route::get('dashboard/pemilih/{id}', 'DashboardController@pemilih');
            
            // Voting
            Route::post('voting/cast', 'VotingController@cast');
            Route::get('voting/history/{pemilihId}', 'VotingController@history');
        });

        // Common authenticated routes (both admin and pemilih)
        Route::get('kandidat', 'KandidatController@index');
        Route::get('kandidat/{id}', 'KandidatController@show');
    });
});

// Fallback for undefined API routes
Route::fallback(function(){
    return response()->json([
        'status' => 'error',
        'message' => 'API endpoint not found'
    ], 404);
});
