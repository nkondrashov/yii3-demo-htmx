<?php

use App\ToDo\Todo;
use Nkondrashov\Yii3\Htmx\HTMX;
use Yiisoft\Html\Html;
use Yiisoft\Http\Method;

/** @var Todo $todo */
/** @var array|null $errors */

$attributes = [
    'class' => 'form-control',
    'placeholder' => 'Todos note'
];

//As example Bootstrap 5 tooltip with errors
if(isset($errors) && !empty($errors)){
    $attributes['data-bs-toggle'] = 'tooltip';
    $attributes['data-bs-placement'] = 'right';
    $attributes['data-bs-trigger'] = 'hover focus';
    $attributes['title'] = implode('; ', current($errors));
    $attributes['class'] .=  ' text-danger is-invalid';
}

$tag = Html::input(
    'text',
    'note',
    $todo->note,
    $attributes);

echo HTMX::make($tag)
    ->request(Method::PUT, '/todo/note/' . $todo->id)
    ->setSwap('outerHTML')
    ->addTriggers('keyup[keyCode==13]', 'keyup[keyCode==27]');
