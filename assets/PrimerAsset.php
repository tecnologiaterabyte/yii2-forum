<?php

namespace terabyte\forum\assets;

class PrimerAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/primer-css/css';
    /**
     * @inheritdoc
     */
    public $css = [
        'primer.css',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'terabyte\forum\assets\PrimerOcticonsAsset',
        /*'terabyte\forum\assets\PrimerMarkdownAsset',*/
    ];
}