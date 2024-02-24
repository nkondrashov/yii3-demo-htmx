<?php


use App\ToDo\Todo;
use Nkondrashov\Yii3\Htmx\HTMX;
use Yiisoft\Html\Html;
use Yiisoft\Http\Method;

/** @var Todo $todo */

$tag = Html::button('[ X ]', ['class' => 'btn btn-outline-danger btn-sm']);

$htmx = HTMX::make($tag)
    ->request(Method::DELETE, '/todo/delete/' . $todo->id)
    ->setSwap('outerHTML')
    ->runOnClick();

if (!$todo->is_complete) {
    $htmx->addConfirm('Are you sure?');
}

echo $htmx;
