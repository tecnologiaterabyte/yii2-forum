<?php

namespace terabyte\forum\assets;

/**
 * AppAsset represents a collection of asset files, such as CSS, JS, images.
 */
class ForumAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@terabyte/forum/assets';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/main.css',
        'css/dev.css',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'terabyte\forum\assets\PrimerAsset',
    ];
}