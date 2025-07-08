<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the Application configuration in bootstrap/app.php
| and are automatically assigned to the "api" middleware group.
|
*/

// Article routes
Route::prefix('articles')->group(function () {
  Route::get('/', [ArticleController::class, 'index']);
  Route::get('/search', [ArticleController::class, 'search']);
  Route::get('/preferences', [ArticleController::class, 'getByPreferences']);
  Route::get('/categories', [ArticleController::class, 'getCategories']);
  Route::get('/sources', [ArticleController::class, 'getSources']);
  Route::get('/authors', [ArticleController::class, 'getAuthors']);
});
