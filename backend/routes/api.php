<?php

declare(strict_types=1);

use App\Property\Infra\Http\Controllers\CheckOwnerEmailController;
use App\Property\Infra\Http\Controllers\GetAllPropertiesController;
use App\Property\Infra\Http\Controllers\CreatePropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    return response()->json(['message' => 'Hello World!']);
});

Route::get('/properties/check-owner-email', CheckOwnerEmailController::class.'@__invoke')->middleware('api');
Route::get('/properties', GetAllPropertiesController::class.'@__invoke')->middleware('api');
Route::post('/properties', CreatePropertyController::class.'@__invoke')->middleware('api');
