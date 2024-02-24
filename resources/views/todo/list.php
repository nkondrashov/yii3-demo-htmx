<?php

declare(strict_types=1);

/**
 * @var WebView $this
 * @var Todo[] $todos
 * @var boolean $hasCompleted
 * @var TranslatorInterface $translator
 * @var ApplicationParameters $applicationParameters
 */

use App\ApplicationParameters;
use App\ToDo\Todo;
use Nkondrashov\Yii3\Htmx\HTMX;
use Yiisoft\Html\Html;
use Yiisoft\Http\Method;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\View\WebView;

$this->setTitle($applicationParameters->getName());
?>
<?php foreach ($todos as $todo): ?>
    <div class="row mb-2">
        <div class="col-1">
            <?= $this->render('controls/check', ['todo' => $todo]); ?>
        </div>

        <div class="col-9">
            <?= $this->render('note/view', ['todo' => $todo]); ?>
        </div>

        <div class="col-2">
            <?= $this->render('controls/delete', ['todo' => $todo]); ?>
        </div>
    </div>
<?php endforeach; ?>

<?php if ($hasCompleted): ?>
    <div class="d-grid gap-2">
        <?= HTMX::make(Html::button('[ Delete all completed ]', ['class' => 'btn btn btn-light']))
            ->request(Method::DELETE, '/todo/delete-completed')
            ->setSwap('none')
            ->runOnClick(); ?>
    </div>
<?php endif; ?>
