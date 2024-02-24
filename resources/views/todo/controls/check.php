<?php

/** @var Todo $todo */

use App\ToDo\Todo;
use Nkondrashov\Yii3\Htmx\HTMX;
use Yiisoft\Html\Html;
use Yiisoft\Http\Method;

$tag = Html::checkbox(
    'is_complete',
    $todo->is_complete,
    ['class' => 'form-check-input', 'checked' => $todo->is_complete]);

echo HTMX::make($tag)
    ->request(Method::PUT, '/todo/check/' . $todo->id)
    ->setSwap('outerHTML')
    ->triggerCustomEventAfterRequest('updateList')
    ->runOnChange();

