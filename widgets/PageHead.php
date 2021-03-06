<?php

namespace terabyte\forum\widgets;

use Yii;
use yii\base\Widget;

class PageHead extends Widget
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $subtitle;
    /**
     * @var array
     */
    public $options;
    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!isset($this->title)) {
            return null;
        }
        $options['class'] = 'pagehead';
        if (Yii::$app->controller->route == 'site/error') {
            $options['class'] = 'pagehead pagehead-error';
        }
        echo $this->render('pagehead', [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'options' => $options
        ]);
    }
}
