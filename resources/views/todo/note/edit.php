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
    ['class' => 'form-control', 'placeholder' => 'Todos note']);

echo HTMX::make($tag)
    ->request(Method::PUT, '/todo/note/' . $todo->id)
    ->setSwap('outerHTML')
    ->addTriggers('keyup[keyCode==13]', 'keyup[keyCode==27]');

