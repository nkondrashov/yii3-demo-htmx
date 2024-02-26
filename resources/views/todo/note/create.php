<?php

use App\ToDo\Todo;
use Nkondrashov\Yii3\Htmx\HTMX;
use Yiisoft\Html\Html;
use Yiisoft\Http\Method;

/** @var Todo $todo */
/** @var array|null $errors */

$has_errors = isset($errors) && !empty($errors);

$tag = Html::input(
    'text',
    'note',
    $todo->note,
    [
        'class' => 'form-control ' . ($has_errors ? 'text-danger is-invalid' : ''),
        'placeholder' => 'Add new note here',
        'autofocus' => true,
        'autocomplete' => 'off'
    ]);

echo HTMX::make($tag)
    ->request(Method::POST, '/todo/create')
    ->setTarget('#createForm')
    ->addTriggers('keyup[keyCode==13]');

//As example navive-render errors
if ($has_errors) {
    echo $this->render('_errors', ['errors' => $errors]);
}


