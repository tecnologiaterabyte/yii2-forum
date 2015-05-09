<?php

namespace terabyte\forum\assets;

class PostAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@terabyte/forum/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/post.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset'
    ];
}