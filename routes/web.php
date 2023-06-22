<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SubController;
use App\Http\Controllers\UserController;
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
Route::get('/clear', function() {
    Artisan::call('optimize:clear');
    Artisan::call('storage:link');
    return '!!!!!!!!!!!!';
});

Route::view('/info', 'about')->name('info');

Route::controller(GameController::class)->group(function (){
    Route::get('/', 'index')->name('games.index');
    Route::get('/catalog/filter','filter')->name( 'games.filter');
    Route::get('/catalog/search', 'searchResults')->name('games.search');
    Route::post('/catalog/show/download', 'download')->name('games.download');
    Route::get('/catalog/{id?}', 'catalog')->name('games.catalog');
    Route::get('/user/tab/{user}/{tab_id}', 'getTab')->name('games.filter-tab');
    Route::get('/catalog/show/{game}','show')->name( 'games.show');
});

Route::controller(UserController::class)->group(function(){
   Route::get('/registration', 'create')->name('user.reg');
    Route::get('/profile/{user?}', 'index')->name('user.profile');
   Route::post('/reg/store', 'store')->name('user.store');
   Route::get('/login', 'login')->name('login');
   Route::post('/login/check', 'loginCheck')->name('login.check');
});

Route::middleware('auth')->group( function(){
    Route::controller(UserController::class)->group(function(){
        Route::get('/logout', 'logout')->name('logout');
        Route::get('/edit', 'edit')->name('user.edit');
        Route::get('/edit/password', 'editPass')->name('user.edit.pass');
        Route::patch('/profile/update', 'update')->name('user.update');
        Route::post('/profile/update/pass', 'updatePass')->name('user.update.pass');
    });

    Route::controller(GameController::class)->group(function (){
        Route::get('/game/create', 'create')->name('games.create');
        Route::get('/game/edit/{game}', 'edit')->name('games.edit');
        Route::post('/game/like', 'addLike')->name('games.like');
        Route::post('/game/store', 'store')->name('games.store');
        Route::post('/game/update', 'update')->name('games.update');
        Route::get('/game/hide/{game}', 'hide')->name('games.hide');
        Route::get('/game/open/{game}', 'open')->name('games.open');
        Route::post('/game/status', 'changeStatus')->name('games.status');
        Route::delete('/game/delete/{game}', 'delete')->name('games.delete');
        Route::post('/game/edit/delImg', 'delScreen')->name('games.imgDel');
        Route::post('/game/edit/delTag', 'delTag')->name('games.tagDel');
        Route::post('/game/dislike','dislike')->name( 'games.dislike');
    });

    Route::controller(CommentsController::class)->group( function(){
        Route::post('/comment/store/{game}', 'store')->name('comments.store');
        Route::delete('/comment/del/{comment}', 'destroy')->name('comments.delete');
        Route::post('/comment/update/{comment}', 'update')->name('comments.update');
    });

    Route::controller(SubController::class)->group(function(){
        Route::post('/sub/{user}', 'sub')->name('sub.make');
        Route::delete('/unsub/{user}', 'unsub')->name('sub.del');
    });
});

