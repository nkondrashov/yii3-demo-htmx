<?php

declare(strict_types=1);

/**
 * @var WebView $this
 * @var TranslatorInterface $translator
 * @var ApplicationParameters $applicationParameters
 */

use App\ApplicationParameters;
use Nkondrashov\Yii3\Htmx\HTMX;
use Yiisoft\Html\Html;
use Yiisoft\Http\Method;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\View\WebView;

$this->setTitle($applicationParameters->getName());
?>

<div class="row justify-content-md-center">
    <div class="col col-md-1">
    </div>
    <div class="col col-md-4">
        <h1 class="text-center">To Do</h1>
        <?= HTMX::make(Html::tag('div'))
            ->request(Method::GET, '/todo/create')
            ->runOnLoad(); ?>
        <hr>
        <?= HTMX::make(Html::tag('div'))
            ->request(Method::GET, '/todo/list')
            ->addTriggersOnCustomEvents('updateList')
            ->runOnLoad(); ?>
    </div>
    <div class="col col-md-1">
    </div>
</div>
