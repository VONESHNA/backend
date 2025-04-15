<?php

use App\Http\Controllers\ApiController;

//api view


    Route::post('/apiregister', [ApiController::class, 'register']);
    Route::post('/apilogin', [ApiController::class, 'login']);
   // Route::middleware('auth:api')->post('apilogout', [ApiController::class, 'logout']);
    
        
        // CRUD routes for users
    Route::get('/apiusers', [AuthController::class, 'index']);
    Route::get('/apiusers/{id}', [AuthController::class, 'show']);
    Route::put('/apiusers/{id}', [AuthController::class, 'update']);
    Route::delete('/apiusers/{id}', [AuthController::class, 'destroy']);
    
    //api view