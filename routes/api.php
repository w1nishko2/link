<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhotoEditorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Остальные API роуты для фоторедактора (если нужны в будущем)
// Route::middleware('auth')->prefix('photo-editor')->group(function () {
//     Route::get('/current-images', [PhotoEditorController::class, 'getCurrentImages']);
//     Route::delete('/delete-image', [PhotoEditorController::class, 'deleteImage']);
// });
