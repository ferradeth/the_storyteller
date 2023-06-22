<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\admin\LoginController::class)->group(function () {
        Route::get('/', 'login')->name('login');
        Route::post('/check', 'loginCheck')->name('login.check');
    });
    
    Route::controller(\App\Http\Controllers\admin\SubController::class)->group( function(){
        Route::get('/subs/confirm/{sub}', 'confirm')->name('subs.confirm');
    });

    Route::middleware('auth:admin')->group(function (){

        Route::controller(\App\Http\Controllers\admin\LoginController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('index');
            Route::get('/logout', 'logout')->name('logout');
        });

        Route::controller(\App\Http\Controllers\admin\AdminController::class)->group(function (){
            Route::get('/admins', 'index')->name('admins.index');
            Route::post('/admins/create', 'create')->name('admins.create');
            Route::post('/admins/update/', 'update')->name('admins.update');
            Route::delete('admins/delete/{admin}', 'delete')->name('admins.delete');
        });

        Route::controller(\App\Http\Controllers\admin\GameController::class)->group(function (){

            Route::get('/games/check/', 'check_games')->name('games.check');
            Route::get('/games/canceled/', 'canceled_games')->name('games.canceled');
            Route::get('/games/show/{game}', 'show')->name('games.show');

            Route::get('/games/confirm/', 'confirm')->name('games.confirm');
            Route::get('/games/cancel/', 'cancel')->name('games.cancel');

            Route::get('/games/ban/', 'ban')->name('games.ban');
            Route::get('/games/unban/', 'unban')->name('games.unban');

            Route::get('/games/{user_id?}', 'index')->name('games.index');

        });

        Route::controller(\App\Http\Controllers\admin\UserController::class)->group(function(){
            Route::get('/users', 'index')->name('users.index');
            Route::get('/users/ban/{user}', 'ban')->name('users.ban');
            Route::get('/users/unban/{user}', 'unban')->name('users.unban');
            Route::get('/users/follow/{user}', 'followers')->name('users.followers');
            Route::get('/users/subscribes/{user}', 'subscribes')->name('users.subscribes');
        });

        Route::controller(\App\Http\Controllers\admin\TagController::class)->group(function (){
           Route::get('/tags', 'index')->name('tags.index');
           Route::post('/tags/create', 'create')->name('tags.create');
           Route::post('/tags/update/{tag?}', 'update')->name('tags.update');
           Route::delete('tags/delete/{tag}', 'delete')->name('tags.delete');
        });

        Route::controller(\App\Http\Controllers\admin\CommentController::class)->group(function (){
            Route::get('/comments', 'index')->name('comments.index');
//            Route::post('/comments/{comment}', 'delete')->name('comments.delete');
            Route::post('/comments/', 'delete')->name('comments.delete');
        });

        Route::controller(\App\Http\Controllers\admin\SubController::class)->group( function(){
           Route::get('/subs', 'index')->name('subs.index');
           Route::delete('/subs/{sub}', 'delete')->name('subs.del');
        });
    });




