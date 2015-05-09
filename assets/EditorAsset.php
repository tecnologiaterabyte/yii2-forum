<?php

namespace terabyte\forum\assets;

class EditorAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@terabyte\forum\assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/font-awesome.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'js/editor.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'editor\AtwhoAsset',
        'editor\CaretAsset',
        'editor\RangyInputsAsset',
    ];
}