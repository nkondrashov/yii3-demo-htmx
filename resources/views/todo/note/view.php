<?php

/** @var Todo $todo */

use App\ToDo\Todo;
use Nkondrashov\Yii3\Htmx\HTMX;
use Nkondrashov\Yii3\Htmx\HTMXHeaderManager;
use Yiisoft\Html\Html;
use Yiisoft\Http\Method;

/** @var HTMXHeaderManager $HTMXHeaderManager */

$options = [];
if ($todo->is_complete) {
    $options['style'] = 'text-decoration: line-through;';
}
$tag = Html::tag('i', $todo->note, $options);

echo HTMX::make($tag)
    ->request(Method::PUT, '/todo/note/' . $todo->id)
    ->setSwap('outerHTML')
    ->addTriggers('dblclick');


