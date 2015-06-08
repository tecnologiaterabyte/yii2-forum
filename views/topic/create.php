<?php

use yii\helpers\Url;
use terabyte\forum\components\View;
use terabyte\forum\models\Forum;
use terabyte\forum\widgets\EditorWidget;
use terabyte\forum\widgets\pageHead;

/* @var View $this
 * @var $model
 * @var Forum $forum */

$this->title = Yii::t('forum', 'Создать тему');
$this->subtitle = Yii::t('forum', 'вернуться в раздел') . ' <a href="' . Url::to(['forum/view', 'id' => $forum->id]) . '">' . $forum->name . '</a>';

$this->params['breadcrumbs'][] = ['label' => Yii::t('forum', 'Main Board'), 'url' => ['forum/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('forum', $forum->name), 'url' => ['topic/view', 'id' => $forum->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= pageHead::widget(['title' => $this->title, 'subtitle' => $this->subtitle]) ?>

<div class="page-create-topic">
    <?= EditorWidget::widget([
        'model' => $model,
        'titleAttribute' => 'subject',
        'messageAttribute' => 'message',
    ]) ?>
</div>
