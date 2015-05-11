<?php

namespace terabyte\forum\assets;

class PrimerMarkdownAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/primer-markdown/dist';
    /**
     * @inheritdoc
     */
    public $css = [
        'user-content.css',
    ];
}