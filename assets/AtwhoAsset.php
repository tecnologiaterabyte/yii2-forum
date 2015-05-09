<?php

namespace terabyte\forum\assets;

class AtwhoAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/jquery.atwho/dist';
    /**
     * @inheritdoc
     */
    public $js = [
        'js/jquery.atwho.js',
    ];
    /**
     * @inheritdoc
     */
    public $css = [
        'css/jquery.atwho.css',
    ];
}