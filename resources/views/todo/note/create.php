<?php

/** @var Todo $todo */

use App\ToDo\Todo;
use Nkondrashov\Yii3\Htmx\HTMX;
use Yiisoft\Html\Html;
use Yiisoft\Http\Method;


$tag = Html::input(
    'text',
    'note',
    $todo->note,
    [
        'class' => 'form-control',
        'placeholder' => 'Add new note here',
        'autofocus' => true,
        'autocomplete' => 'off'
    ]);

echo HTMX::make($tag)
    ->request(Method::POST, '/todo/create')
    ->setSwap('outerHTML')
    ->addTriggers('keyup[keyCode==13]');



