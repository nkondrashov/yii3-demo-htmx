<?php
/** @var array $errors */
?>
<div class="errors text-danger">
    <?php foreach ($errors as $attr => $list): ?>
        <b><?= $attr; ?></b>: <?= implode('; ', $list); ?>
    <?php endforeach; ?>
</div>
