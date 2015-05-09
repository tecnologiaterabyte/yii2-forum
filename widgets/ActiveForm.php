<?php

namespace terabyte\forum\widgets;

class ActiveForm extends \yii\widgets\ActiveForm
{
    /**
     * @inheritdoc
     */
    public $fieldClass = 'terabyte\forum\widgets\ActiveField';
    /**
     * @inheritdoc
     */
    public $encodeErrorSummary = false;
    /**
     * @inheritdoc
     */
    public $enableClientValidation = false;
    /**
     * @inheritdoc
     */
    public $enableClientScript = false;
}