<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Proposal\Infra\Http\Controllers\CreateProposalController;

Route::get('/', function (Request $request) {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('/proposals', CreateProposalController::class.'@__invoke')->middleware('api');
