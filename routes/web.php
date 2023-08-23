<?php

use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Category\CreateController;
use App\Http\Controllers\Admin\Category\DestroyController;
use App\Http\Controllers\Admin\Category\EditController;
use App\Http\Controllers\Admin\Category\ShowController;
use App\Http\Controllers\Admin\Category\StoreController;
use App\Http\Controllers\Admin\Category\UpdateController;
use App\Http\Controllers\Admin\Main\MainController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Post\IndexController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::namespace('Main')->group(function () {
    Route::get('/', [\App\Http\Controllers\Main\IndexController::class, '__invoke'])->name('main.index');
});
Route::namespace('Post')->prefix('posts')->group(function () {
    Route::get('/', [IndexController::class, '__invoke'])->name('post.index');
    Route::get('/{post}', [\App\Http\Controllers\Post\ShowController::class, '__invoke'])->name('post.show');

    Route::namespace('Comment')->prefix('{post}/comment')->group(function () {
        Route::post('/', [\App\Http\Controllers\Post\Comment\StoreController::class, '__invoke'])->name('post.comment.store');
    });

    Route::namespace('like')->prefix('{post}/like')->group(function () {
        Route::post('/', [\App\Http\Controllers\Post\Like\StoreController::class, '__invoke'])->name('post.like.store');
    });
});

Route::namespace('Category')->prefix('categories')->group(function () {
    Route::get('/', [\App\Http\Controllers\Category\IndexController::class, '__invoke'])->name('category.index');

    Route::namespace('Post')->prefix('{category}/posts')->group(function () {
        Route::get('/', [\App\Http\Controllers\Category\Post\IndexController::class, '__invoke'])->name('category.post.index');
    });
});

Route::namespace('Personal')->prefix('personal')->middleware(['auth', 'verified'])->group(function () {
    Route::namespace('Main')->prefix('main')->group(function () {
        Route::get('/', [\App\Http\Controllers\Personal\Main\MainController::class, '__invoke'])->name('personal.main.index');
    });
    Route::namespace('liked')->prefix('liked')->group(function () {
        Route::get('/', [App\Http\Controllers\Personal\Liked\LikedController::class, '__invoke'])->name('personal.liked.index');
        Route::delete('/{post}', [\App\Http\Controllers\Personal\Liked\DestroyController::class, '__invoke'])->name('personal.liked.delete');
    });
    Route::namespace('Comment')->prefix('comment')->group(function () {
        Route::get('/', [\App\Http\Controllers\Personal\Comment\CommentController::class, '__invoke'])->name('personal.comment.index');
        Route::get('/{comment}/edit', [\App\Http\Controllers\Personal\Comment\EditController::class, '__invoke'])->name('personal.comment.edit');
        Route::patch('/{comment}', [\App\Http\Controllers\Personal\Comment\UpdateController::class, '__invoke'])->name('personal.comment.update');
        Route::delete('/{comment}', [\App\Http\Controllers\Personal\Comment\DestroyController::class, '__invoke'])->name('personal.comment.delete');
    });
});

Route::namespace('Admin')->prefix('admin')->middleware(['auth', 'admin', 'verified'])->group(function () {
    Route::namespace('Main')->group(function () {
        Route::get('/', [MainController::class, '__invoke'])->name('admin.main.index');
    });
    Route::namespace('Category')->prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, '__invoke'])->name('admin.category.index');
        Route::get('/create', [CreateController::class, '__invoke'])->name('admin.category.create');
        Route::post('/', [StoreController::class, '__invoke'])->name('admin.category.store');
        Route::get('/{category}', [ShowController::class, '__invoke'])->name('admin.category.show');
        Route::get('/{category}/edit', [EditController::class, '__invoke'])->name('admin.category.edit');
        Route::patch('/{category}', [UpdateController::class, '__invoke'])->name('admin.category.update');
        Route::delete('/{category}', [DestroyController::class, '__invoke'])->name('admin.category.delete');
    });

    Route::namespace('Tag')->prefix('tags')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\Tag\TagController::class, '__invoke'])->name('admin.tag.index');
        Route::get('/tag', [\App\Http\Controllers\Admin\Tag\CreateController::class, '__invoke'])->name('admin.tag.create');
        Route::post('/', [\App\Http\Controllers\Admin\Tag\StoreController::class, '__invoke'])->name('admin.tag.store');
        Route::get('/{tag}', [\App\Http\Controllers\Admin\Tag\ShowController::class, '__invoke'])->name('admin.tag.show');
        Route::get('/{tag}/edit', [\App\Http\Controllers\Admin\Tag\EditController::class, '__invoke'])->name('admin.tag.edit');
        Route::patch('/{tag}', [\App\Http\Controllers\Admin\Tag\UpdateController::class, '__invoke'])->name('admin.tag.update');
        Route::delete('/{tag}', [\App\Http\Controllers\Admin\Tag\DestroyController::class, '__invoke'])->name('admin.tag.delete');
    });

    Route::namespace('Post')->prefix('posts')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\Post\PostController::class, '__invoke'])->name('admin.post.index');
        Route::get('/create', [\App\Http\Controllers\Admin\Post\CreateController::class, '__invoke'])->name('admin.post.create');
        Route::post('/', [\App\Http\Controllers\Admin\Post\StoreController::class, '__invoke'])->name('admin.post.store');
        Route::get('/{post}', [\App\Http\Controllers\Admin\Post\ShowController::class, '__invoke'])->name('admin.post.show');
        Route::get('/{post}/edit', [\App\Http\Controllers\Admin\Post\EditController::class, '__invoke'])->name('admin.post.edit');
        Route::patch('/{post}', [\App\Http\Controllers\Admin\Post\UpdateController::class, '__invoke'])->name('admin.post.update');
        Route::delete('/{post}', [\App\Http\Controllers\Admin\Post\DestroyController::class, '__invoke'])->name('admin.post.delete');
    });

    Route::namespace('User')->prefix('users')->group(function () {
        Route::get('/', [UserController::class, '__invoke'])->name('admin.user.index');
        Route::get('/create', [\App\Http\Controllers\Admin\User\CreateController::class, '__invoke'])->name('admin.user.create');
        Route::post('/', [\App\Http\Controllers\Admin\User\StoreController::class, '__invoke'])->name('admin.user.store');
        Route::get('/{user}', [\App\Http\Controllers\Admin\User\ShowController::class, '__invoke'])->name('admin.user.show');
        Route::get('/{user}/edit', [\App\Http\Controllers\Admin\User\EditController::class, '__invoke'])->name('admin.user.edit');
        Route::patch('/{user}', [\App\Http\Controllers\Admin\User\UpdateController::class, '__invoke'])->name('admin.user.update');
        Route::delete('/{user}', [\App\Http\Controllers\Admin\User\DestroyController::class, '__invoke'])->name('admin.user.delete');
    });
});

Auth::routes(['verify' => true]);

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
