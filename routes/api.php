<?php

use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/generate-questionnaire', [QuestionnaireController::class, 'generateQuestionnaire']);
Route::get('/all-questionnaire', [QuestionnaireController::class, 'allQuestionnaire']);

Route::group(['middleware' => ['check.test.completion','check.test.expired']], function () {
    Route::get('/questionnaire/{id}', [QuestionnaireController::class, 'showQuestionnaire']);
    Route::post('/questionnaire/{id}/student/{student_id}', [ResponseController::class, 'storeResponse']);
});
