<?php

declare(strict_types=1);

namespace App\Asset;

use Nkondrashov\Yii3\Htmx\HTMXAsset;
use Yiisoft\Assets\AssetBundle;
use Yiisoft\Yii\Bootstrap5\Assets\BootstrapAsset;

final class AppAsset extends AssetBundle
{
    public ?string $basePath = '@assets';
    public ?string $baseUrl = '@assetsUrl';
    public ?string $sourcePath = '@resources/assets/css';

    public array $css = [
        'site.css',
    ];

    public array $depends = [
        BootstrapAsset::class,
        HTMXAsset::class
    ];
}
