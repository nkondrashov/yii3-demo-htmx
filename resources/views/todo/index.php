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

<style>
    .htmx-indicator {
        display: none;
    }

    .htmx-request .htmx-indicator {
        display: inline;
    }

    .htmx-request.htmx-indicator {
        display: inline;
    }

    .overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        opacity: 0.5;
        transform: translate(-50%, -50%);
    }
</style>
<script>
    document.body.addEventListener('htmx:afterSwap', function (evt) {
        //bootstrap need manual initializations for tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

<div class="row justify-content-md-center">
    <div class="col col-md-1">
    </div>
    <div class="col col-md-4">
        <h1 class="text-center">To Do</h1>
        <?= HTMX::make(Html::tag('div', '', ['id' => 'createForm']))
            ->request(Method::GET, '/todo/create')
            ->runOnLoad(); ?>
        <hr>
        <div class="overlay">
            <div class="d-flex justify-content-center">
                <div id="listSpinner" class="spinner-border htmx-indicator" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <?= HTMX::make(Html::tag('div'))
            ->request(Method::GET, '/todo/list')
            ->addTriggersOnCustomEvents('updateList')
            ->addIndicator('#listSpinner')
            ->runOnLoad(); ?>
    </div>
    <div class="col col-md-1">
    </div>
</div>
