<?php

declare(strict_types=1);

use App\Controller\SiteController;
use App\ToDo\TodoController;
use Nkondrashov\Yii3\Htmx\HTMXMiddleware;
use Yiisoft\Http\Method;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

return [
    Group::create('/{_language}')
        ->middleware(HTMXMiddleware::class)
        ->routes(
            Route::get('/')->action([SiteController::class, 'index'])->name('home'),
        ),
    Group::create('/todo')
        ->middleware(HTMXMiddleware::class)
        ->routes(
            Route::get('/')->action([TodoController::class, 'index'])->name(''),
            Route::get('/list')->action([TodoController::class, 'list'])
                ->name('todo/list'),
            Route::delete( '/delete/{id:\w+}')
                ->action([TodoController::class, 'delete'])
                ->name('todo/delete'),
            Route::delete( '/delete-completed')
                ->action([TodoController::class, 'deleteCompleted'])
                ->name('todo/delete-completed'),
            Route::methods([Method::GET, Method::POST], '/create')
                ->action([TodoController::class, 'create'])
                ->name('todo/create'),
            Route::put('/check/{id:\w+}')
                ->action([TodoController::class, 'check'])
                ->name('todo/check'),
            Route::methods([Method::GET, Method::PUT],'/note/{id:\w+}')
                ->action([TodoController::class, 'note'])
                ->name('todo/note'),
        )
];
